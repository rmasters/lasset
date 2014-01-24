<?php

namespace Lasset\Laravel;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Lasset\Manager;

class LassetServiceProvider extends ServiceProvider
{
    const MANAGER_KEY = 'lasset.manager';

    protected $defer = true;

    public function register()
    {
        $this->bindLasset($this->app);
    }

    protected function bindLasset(Container $app)
    {
        // Load default config from /config/config.php
        $app['config']->package('rmasters/lasset', __DIR__ .'/../config');
        $app->bindShared(self::MANAGER_KEY, function ($app) {
            $manager = new Manager($app['config']->get('lasset'));
            if (is_null($manager->getDefault()) && $manager->hasEnvironment($app['env'])) {
                $manager->setDefault($app['env']);
            }
            return $manager;
        });
    }

    public function provides()
    {
        return array(self::MANAGER_KEY);
    }
}
