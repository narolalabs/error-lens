<?php

namespace Narolalabs\ErrorLens\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Narolalabs\ErrorLens\ErrorLens
 */
class ErrorLens extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Narolalabs\ErrorLens\ErrorLens::class;
    }
}
