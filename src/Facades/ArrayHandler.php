<?php

namespace AliAref\ArrayFilterable\Facades;

use Illuminate\Support\Facades\Facade;

class ArrayHandler extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'array-handler';
    }
}
