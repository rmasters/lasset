<?php

use Mockery as m;
use Lasset\Laravel\LassetServiceProvider;
use Lasset\Manager;
use Lasset\Laravel\Facades\Lasset;

class LaravelTest extends PHPUnit_Framework_TestCase
{
    protected $app;
    protected $lasset;

    public function setUp()
    {
        $app =& $this->app;
        $lasset =& $this->lasset;

        $app = m::mock('Illuminate\Container\Container');

        $app->shouldReceive('bindShared')
            ->once()
            ->with(
                LassetServiceProvider::MANAGER_KEY,
                m::on(function($cb) use ($app, &$lasset) {
                if (is_callable($cb)) {
                    $lasset = $cb($app);
                    return true;
                }
                })
            )
            ->andReturn(m::type('Lasset\Manager'));

        $config = m::mock('Illuminate\Config\Repository');
        $config->shouldReceive('package')
            ->once()
            ->with('rmasters/lasset', m::type('string'))
            ->andReturn($this->getConfig());

        $config->shouldReceive('get')
            ->once()
            ->with('lasset')
            ->andReturn($this->getConfig());

        $app->shouldReceive('offsetGet')
            ->once()
            ->with('config')
            ->andReturn($config);

        $app->shouldReceive('offsetGet')
            ->once()
            ->with('env')
            ->andReturn('production');

    }

    public function testServiceProvider()
    {
        $provider = new LassetServiceProvider($this->app);
        $provider->boot();
        $provider->register();

        $this->assertEquals('production', $this->lasset->getDefault());
        $this->assertEquals('http://static.example.org/foobar', $this->lasset->url('foobar'));
    }

    public function testFacade()
    {
        $provider = new LassetServiceProvider($this->app);
        $provider->boot();
        $provider->register();

        $manager = new Manager($this->getConfig());
        $manager->setDefault('production');
        $this->app->shouldReceive('offsetGet')
            ->once()
            ->with(LassetServiceProvider::MANAGER_KEY)
            ->andReturn($manager);

        Lasset::setFacadeApplication($this->app);
        $this->assertEquals('http://static.example.org/foobar', Lasset::url('foobar'));
    }

    protected function getConfig()
    {
        return array(
            'environments' => array(
                'local' => array(
                    'provider' => 'Lasset\Providers\Host',
                    'options' => array(
                        'baseUrl' => '/assets/',
                    ),
                ),
                'production' => array(
                    'provider' => 'Lasset\Providers\Host',
                    'options' => array(
                        'baseUrl' => 'http://static.example.org/',
                    ),
                ),
            ),
        );
    }
}
