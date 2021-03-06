<?php

use Symfony\CS\FixerInterface;

/**
 * @todo Somehow ignore the PSR-0 warnings? Using PSR-4 here
 */

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->notName('LICENSE')
    ->notName('README.md')
    ->notName('.php_cs')
    ->notName('composer.*')
    ->notName('phpunit.xml*')
    ->exclude('vendor')
    ->in(__DIR__);

return Symfony\CS\Config\Config::create()->finder($finder);
