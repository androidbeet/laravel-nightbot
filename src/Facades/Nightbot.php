<?php

namespace Androidbeet\Nightbot\Facades;

use Illuminate\Support\Facades\Facade;

class Nightbot extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Androidbeet\Nightbot\Nightbot::class;
    }
}
