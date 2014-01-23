<?php

class ProviderTest extends PHPUnit_Framework_TestCase
{
    public function testConfig()
    {
        $config = array(
            'method' => 'foobar',
            'property' => 'barfoo',
        );
        $provider = new StubProvider($config);
        $this->assertEquals('foobar', $provider->method);
        $this->assertEquals('barfoo', $provider->property);
    }
}
