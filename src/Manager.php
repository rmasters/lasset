<?php

namespace Lasset;

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

    public function addProvider($environment, Provider $provider, $default = false)
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

    protected function checkEnvironment($name)
    {
        if (is_null($name)) {
            throw new InvalidArgumentException('No environment given or no default set');
        }

        if (!array_key_exists($name, $this->environments)) {
            throw new InvalidArgumentException('Unknown environment ' . $name);
        }
    }
}
