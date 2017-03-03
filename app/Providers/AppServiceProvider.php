<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('display_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-z0-9 ]+$/i', $value);
        });

        Validator::extend('least_one_letter', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(?=.*[a-zA-Z])/', $value);
        });

        Validator::extend('over_age', function ($attribute, $value, $parameters, $validator) {
            return \App\Helpers\Helper::is_age_over($parameters[0], $value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
