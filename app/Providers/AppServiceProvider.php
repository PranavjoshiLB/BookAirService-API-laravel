<?php

namespace App\Providers;

use App\Exceptions\AmadeusServiceException;
use App\Exceptions\CustomHandler;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Exception;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     *  Handle Http exception;
     */
    public function boot()
    {

    }
}
