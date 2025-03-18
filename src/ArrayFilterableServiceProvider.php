<?php

namespace AliAref\ArrayFilterable;

use Illuminate\Support\ServiceProvider;

class ArrayFilterableServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('array-handler', function () {
            return new DataHandler();
        });
    }

    public function boot()
    {
        //
    }
}
