<?php

namespace Sohel\TraverseCategory\Facades;

use Illuminate\Support\Facades\Facade;

class TraverseCategory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'traverseCategory';
    }
}