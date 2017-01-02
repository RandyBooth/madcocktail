<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::group(['prefix' => 'login'], function() {
    Route::group(['prefix' => '{provider}', 'where' => ['provider' => '[a-z]+']], function($provider) {
        Route::get('/', ['as' => 'oauth.redirect', 'uses' => 'OAuthController@redirect']);
//        Route::get('login', ['as' => 'oauth.login', 'uses' => 'OAuthController@redirect']);
        Route::get('callback', ['as' => 'oauth.callback', 'uses' => 'OAuthController@callback']);
    });
});

Route::get('/', function () {
    return view('welcome');
});

Route::resource('ingredients', 'IngredientController');
