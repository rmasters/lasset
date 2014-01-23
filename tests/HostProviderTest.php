<?php

use Lasset\Providers\Host;

class HostProviderTest extends PHPUnit_Framework_TestCase
{
    public function testProvider()
    {
        $host = new Host;
        $host->setBaseUrl('/components/');
        $this->assertEquals('/components/foobar', $host->url('/foobar'));
    }
}
