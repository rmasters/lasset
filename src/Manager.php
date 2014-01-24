<?php

namespace Lasset;

use Lasset\Providers\ProviderInterface;

use InvalidArgumentException;

class Manager
{
    protected $environments;
    protected $default;
    protected $current;

    public function __construct(array $config = array())
    {
        $this->environments = array();

        $this->configure($config);
    }

    public function configure(array $config)
    {
        if (array_key_exists('environments', $config)) {
            $this->setProviders($config['environments']);
        }

        if (array_key_exists('default', $config)) {
            $this->setDefault($config['default']);
        }
    }

    public function useEnvironment($environment)
    {
        $this->checkEnvironment($environment);
        $this->current = $name;
    }

    public function getEnvironment()
    {
        return $this->current;
    }

    public function addProvider($environment, ProviderInterface $provider, $default = false)
    {
        $this->environments[$environment] = $provider;

        if ($default) {
            $this->setDefault($environment);
        }
    }

    public function getProvider($environment = null)
    {
        // Use the default environment if not given
        $environment = $environment ?: $this->default;
        $this->checkEnvironment($environment);

        return $this->environments[$environment];
    }

    public function setProviders(array $environments)
    {
        foreach ($environments as $env => $provider)
        {
            /**
             * Instantiate providers, we're expecting one of:
             *   array (provider => (string|ProviderInterface), [options => array])
             *   string|ProviderInterface
             */
            if (is_array($provider)) {
                // Key: provider
                if (!array_key_exists('provider', $provider) || !class_exists($provider['provider'])) {
                    throw new InvalidArgumentException('Lasset config for environment ' . $env . ' must contain a \'provider\' class name');
                }

                // Key: options
                $options = array_key_exists('options', $provider) ? $provider['options'] : array();

                // Create the object with options
                $provider = $provider['provider'];
                $provider = new $provider($options);
            } else if (is_string($provider) && class_exists($provider)) {
                // A class name without options
                $provider = new $provider;
            } else if (!is_object($provider)) {
                // Something weird
                throw new InvalidArgumentException('Lasset config for environment ' . $env . ' expects either an array or a provider');
            }

            // Check we have a ProviderInterface
            if (!$provider instanceof ProviderInterface) {
                throw new InvalidArgumentException('Lasset config for environment ' . $env . ', provider must implement Lasset\Providers\ProviderInterface');
            }

            // Add the provider
            $this->addProvider($env, $provider);
        }
    }

    public function setDefault($environment)
    {
        $this->checkEnvironment($environment);
        $this->default = $environment;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function url($filename)
    {
        return $this->getProvider()->url($filename);
    }

    public function hasEnvironment($name)
    {
        return array_key_exists($name, $this->environments);
    }

    protected function checkEnvironment($name)
    {
        if (is_null($name)) {
            throw new InvalidArgumentException('No environment given or no default set');
        }

        if (!$this->hasEnvironment($name)) {
            throw new InvalidArgumentException('Unknown environment ' . $name);
        }
    }
}
