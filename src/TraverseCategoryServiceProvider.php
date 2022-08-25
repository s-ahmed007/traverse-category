<?php

namespace Sohel\TraverseCategory;

use Illuminate\Support\ServiceProvider;

class TraverseCategoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // code...
    }

    public function register()
    {
        $this->app->bind('traverseCategory', function($app){
            return new TraverseCategory();
        });
    }
}
