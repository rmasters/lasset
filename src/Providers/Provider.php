<?php

namespace Lasset\Providers;

abstract class Provider implements ProviderInterface
{
    protected $config;

    public function __construct(array $config = array())
    {
        $this->configure($config);
    }

    public function configure(array $config)
    {
        foreach ($config as $key => $value) {
            if (method_exists($this, $setter = 'set'.ucfirst($key))) {
                call_user_func(array($this, $setter), $value);
            } else {
                $this->$key = $value;
            }
        }
    }

    abstract public function url($filename);
}
