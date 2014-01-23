<?php

namespace Lasset\Providers;

use Lasset\Provider;

class Host implements Provider
{
    protected $baseUrl;

    public function setBaseUrl($url)
    {
        $this->baseUrl = rtrim($url, '/');
    }

    public function url($filename)
    {
        return $this->baseUrl . '/' . ltrim($filename, '/');
    }
}
