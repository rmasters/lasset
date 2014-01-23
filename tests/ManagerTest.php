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

    public function testConfigure()
    {
        $lasset = new Manager;

        $lasset->configure(array(
            'environments' => array(
                'local' => array(
                    'provider' => 'Lasset\Providers\Host',
                    'options' => array(
                        'baseUrl' => '/components',
                    ),
                ),
            ),
        ));
        $this->assertInstanceOf('Lasset\Providers\Host', $lasset->getProvider('local'));
        $this->assertEquals('/components/foobar', $lasset->getProvider('local')->url('foobar'));

        $lasset->configure(array('environments' => array('testing' => 'StubProvider')));
        $this->assertInstanceOf('StubProvider', $lasset->getProvider('testing'));

        $this->setExpectedException('InvalidArgumentException', 'Lasset config for environment broken1, provider must implement Lasset\Providers\ProviderInterface');
        $lasset->configure(array('environments' => array('broken1' => new \stdclass)));

        $this->setExpectedException('InvalidArgumentException', 'Lasset config for environment broken2, provider must implement Lasset\Providers\ProviderInterface');
        $lasset->configure(array('environments' => array('broken2' => 'ArrayObject')));

        $this->setExpectedException('InvalidArgumentException', 'Lasset config for environment broken3 expects either an array or a provider');
        $lasset->configure(array('environments' => array('broken3' => 123)));

        $this->setExpectedException('InvalidArgumentException', 'Lasset config for environment broken4 must contain a \'provider\' class name');
        $lasset->configure(array('environments' => array('broken4' => array('options' => array()))));
    }

    protected function getMockProvider()
    {
        return m::mock('Lasset\Providers\ProviderInterface');
    }
}
