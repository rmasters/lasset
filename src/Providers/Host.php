<?php

namespace Lasset\Providers;

class Host extends Provider
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
