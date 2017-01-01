<?php

$service_redirect = env('APP_URL', 'http://localhost').'/login';

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

//    'mailgun' => [
//        'domain' => env('MAILGUN_DOMAIN'),
//        'secret' => env('MAILGUN_SECRET'),
//    ],
//
//    'ses' => [
//        'key' => env('SES_KEY'),
//        'secret' => env('SES_SECRET'),
//        'region' => 'us-east-1',
//    ],
//
//    'sparkpost' => [
//        'secret' => env('SPARKPOST_SECRET'),
//    ],
//
//    'stripe' => [
//        'model' => App\User::class,
//        'key' => env('STRIPE_KEY'),
//        'secret' => env('STRIPE_SECRET'),
//    ],

    'facebook' => array(
        'client_id'     => '632169013530721',
        'client_secret' => 'e62c8d0de3f4fd9285b81f045d621846',
        'redirect'      => $service_redirect.'/facebook/callback',
    ),

    'google' => array(
        'client_id'     => '754238603270-jjfnqn8e0e01dfvq74k4b38vt59l53sh.apps.googleusercontent.com',
        'client_secret' => 'q63tyPD_alxhNkIz_WpwTYGH',
        'redirect'      => $service_redirect.'/google/callback',
    ),

    'twitter' => array(
        'client_id'     => 'pu3xY9j7k5tfzYhgVjkQFNqYV',
        'client_secret' => 'vMrsvDyAxHhBGbGQeVlqJTAFbohosuSkkuID9StJuwQNazhPgE',
        'redirect'      => $service_redirect.'/twitter/callback',
    ),

];
