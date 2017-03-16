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

        Validator::replacer('over_age', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':age', $parameters[0], $message);
        });

        Validator::extend('domain_contains', function ($attribute, $value, $parameters, $validator) {
            $strict = true;

            if (isset($parameters[1])) {
                $strict = filter_var($parameters[1], FILTER_VALIDATE_BOOLEAN);
            }

            return \App\Helpers\Helper::get_domain($value, $strict) == $parameters[0];
        });

        Validator::replacer('domain_contains', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':domain', $parameters[0], $message);
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
