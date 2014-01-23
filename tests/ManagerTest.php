<?php

use Lasset\Manager;
use Mockery as m;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    public function testWithoutProviders()
    {
        $lasset = new Manager;

        $this->setExpectedException('InvalidArgumentException', 'No environment given or no default set');
        $lasset->getProvider();

        $this->setExpectedException('InvalidArgumentException', 'Unknown environment local');
        $lasset->useEnvironment('local');

        $this->setExpectedException('InvalidArgumentException', 'Unknown environment local');
        $lasset->getProvider('local');

        $this->setExpectedException('InvalidArgumentException', 'No environment given or no default set');
        $lasset->url('foobar.css');
    }

    public function testProviders()
    {
        $lasset = new Manager;

        $provider = $this->getMockProvider();
        $provider->shouldReceive('url')->once()->with('foobar')->andReturn('barfoo');

        $lasset->addProvider('local', $provider);
        $this->assertSame($provider, $lasset->getProvider('local'));

        $this->assertEquals('barfoo', $lasset->getProvider('local')->url('foobar'));
    }

    public function testDefault()
    {
        $lasset = new Manager;

        $this->assertNull($lasset->getDefault());

        $provider = $this->getMockProvider();
        $provider->shouldReceive('url')->once()->with('foobar')->andReturn('barfoo');

        $lasset->addProvider('local', $provider, true);
        $this->assertEquals('local', $lasset->getDefault());

        $lasset->addProvider('testing', $provider);
        $lasset->setDefault('testing');
        $this->assertSame('testing', $lasset->getDefault());

        $this->assertEquals('barfoo', $lasset->url('foobar'));
    }

    protected function getMockProvider()
    {
        return m::mock('Lasset\Provider');
    }
}
