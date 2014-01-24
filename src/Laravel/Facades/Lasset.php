<?php

namespace Lasset\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Lasset\Laravel\LassetServiceProvider;

class Lasset extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LassetServiceProvider::MANAGER_KEY;
    }
}
