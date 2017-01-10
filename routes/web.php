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

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::group(['prefix' => 'login'], function() {
    Route::group(['prefix' => '{provider}', 'where' => ['provider' => '[a-z]+']], function() {
        Route::get('/', ['as' => 'oauth.redirect', 'uses' => 'OAuthController@redirect']);
        Route::get('callback', ['as' => 'oauth.callback', 'uses' => 'OAuthController@callback']);
    });
});

Route::resource('ingredients', 'IngredientController', [
    'except' => [
        'show'
    ],
    'parameters' => [
        'ingredients' => 'token'
    ]
]);

Route::get('ingredients/{parameters?}', ['as' => 'ingredients.show', 'uses' => 'IngredientController@show'])->where('parameters', '(.*)');

Route::get('r/{token}', ['as' => 'r.show_token', 'uses' => 'RecipeController@show', function($token) {}])->where(['token' => '[A-Za-z0-9]+']);

Route::resource('recipes', 'RecipeController');

Route::resource('occasions', 'OccasionController');
