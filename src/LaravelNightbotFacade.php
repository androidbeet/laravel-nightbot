<?php

namespace Androidbeet\LaravelNightbot;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Androidbeet\LaravelNightbot\Skeleton\SkeletonClass
 */
class LaravelNightbotFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-nightbot';
    }
}
