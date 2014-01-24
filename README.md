# lasset: asset-linker

[![Build Status](https://travis-ci.org/rmasters/lasset.png?branch=master)](https://travis-ci.org/rmasters/lasset) [![Latest Stable Version](https://poser.pugx.org/rmasters/lasset/version.png)](https://packagist.org/packages/rmasters/lasset) [![License](https://poser.pugx.org/rmasters/lasset/license.png)](https://packagist.org/packages/rmasters/lasset) [![Coverage Status](https://coveralls.io/repos/rmasters/lasset/badge.png)](https://coveralls.io/r/rmasters/lasset) [![Dependency Status](https://www.versioneye.com/user/projects/52e25122ec13753189000002/badge.png)](https://www.versioneye.com/user/projects/52e25122ec13753189000002)

Providers helpers that make linking to frontend assets easy, based on your
current environment.

This package is not interested in any compilation steps, pre-processing,
minification, etc. Use grunt, gulp or Assetic for that.

## Plan

Aims to provide configurable links for assets that are:

-   Locally hosted (e.g. `/public/assets`)
-   Hosted on a different domain (e.g. `http://static.example.com/`)
-   Presets for CDN hosting - S3, Akamai, Cloudflare, etc.

## Installation

Install using Composer: `composer require rmasters/lasset:dev-master`

> This library uses the new PSR-4 autoloader. You might need to update composer
> itself to use this - `composer self-update`

## Framework integrations

### Standalone

1.  Instantiate a manager,
2.  Configure, with [an array](src/config/config.php) or by adding providers,
3.  Set the default environment,
4.  Call the `url()`, function.

Usage:

    use Lasset\Manager;

    $manager = new Manager;
    $manager->configure(['environments' => [...]]);
    $manager->addProvider('testing', new HostProvider([...]));

    $manager->setDefault(APP_ENV);

    echo $manager->url('bootstrap/dist/css/bootstrap.min.css');

    // Or to get a specific environment's url
    echo $manager->getProvider('testing')->url('jquery/jquery.min.js');

### Laravel 4

Bundled in this framework is a ServiceProvider and Facade for integration into
Laravel 4. This adds the following functionality:

-   A single Manager instance created under the `lasset.manager` key
-   Facade wraps around the Manager instance
-   Configurable by placing a file in `app/config/packages/rmasters/lasset/config.php`
-   If it exists in the Manager (after loading configuration), the current
    Laravel environment (the result of `App::env()`) will be set as the default
    Lasset environment.

To enable these, simply register the service provider and optionally alias the
facade in `app/config/app.php`:

    'providers' => array(
        // ...
        'Lasset\Laravel\LassetServiceProvider',
    ),

    'aliases' => array(
        'Lasset' => 'Lasset\Laravel\Facades\Lasset',
    ),
