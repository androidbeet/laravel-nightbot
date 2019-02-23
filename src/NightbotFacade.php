<?php

namespace Androidbeet\Nightbot;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Androidbeet\LaravelNightbot\Skeleton\SkeletonClass
 */
class NightbotFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'nightbot';
    }
}
