<?php

namespace Bfg\Attributes\Facades;

use Bfg\Attributes\Attributes;
use Illuminate\Support\Facades\Facade;

/**
 * Class AttributesFacade
 * @package Bfg\Attributes\Facades
 */
class AttributesFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Attributes::class;
    }
}
