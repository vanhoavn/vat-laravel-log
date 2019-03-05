<?php

namespace VZT\Laravel\VZTLog\Facades;

use Illuminate\Support\Facades\Facade;
use VZT\Laravel\VZTLog\Logic\VZTLogLogic;

/**
 * @see VZTLogLogic
 */
class VZTLogModule extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return VZTLogLogic::class;
    }
}
