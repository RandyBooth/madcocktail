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
