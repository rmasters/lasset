# asset-linker

Providers helpers that make linking to frontend assets easy, based on your
current environment.

This package is not interested in any compilation steps, pre-processing,
minification, etc. Use grunt, gulp or Assetic for that.

## Plan

Aims to provide configurable links for assets that are:

-   Locally hosted (e.g. `/public/assets`)
-   Hosted on a different domain (e.g. `http://static.example.com/`)
-   Presets for CDN hosting - S3, Akamai, Cloudflare, etc.

## Framework integrations

### Standalone

1.  Instantiate a manager,
2.  Configure, with [an array](config/config.php) or by adding providers,
3.  Set the default environment,
4.  Call the `url()`, function.

    use Lasset\Manager;

    $manager = new Manager;
    $manager->configure(['environments' => [...]]);
    $manager->addProvider('testing', new HostProvider([...]));

    $manager->setDefault(APP_ENV);

    echo $manager->url('bootstrap/dist/css/bootstrap.min.css');

    // Or to get a specific environment's url
    echo $manager->getProvider('testing')->url('jquery/jquery.min.js');
