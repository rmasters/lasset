<?php

use Lasset\Providers\Provider;

class StubProvider extends Provider
{
    public $method;
    public $property;

    public function setMethod($value)
    {
        $this->method = $value;
    }

    public function url($filename)
    {
        return $filename;
    }
}
