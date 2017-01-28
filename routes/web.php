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

Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
    Route::group(['prefix' => 'ingredients'], function () {
        Route::get('tree', 'IngredientController@tree');
    });
});

Auth::routes();

Route::group(['prefix' => 'login'], function() {
    Route::group(['prefix' => '{provider}', 'where' => ['provider' => '[a-z]+']], function() {
        Route::get('/', ['as' => 'oauth.redirect', 'uses' => 'OAuthController@redirect']);
        Route::get('callback', ['as' => 'oauth.callback', 'uses' => 'OAuthController@callback']);
    });
});

Route::get('/', function () {
    return view('home');
});

Route::get('autocomplete', ['before' => 'csrf', 'as' => 'autocomplete', 'uses' => 'AutocompleteController@search']);
Route::post('autocomplete', ['before' => 'csrf', 'middleware' => 'throttle:50,5', 'as' => 'autocomplete', 'uses' => 'AutocompleteController@search']);


Route::resource('ingredients', 'IngredientController', [
    'except' => [
        'show'
    ],
    'parameters' => [
        'ingredients' => 'token'
    ]
]);

Route::get('ingredients/{parameters?}', ['as' => 'ingredients.show', 'uses' => 'IngredientController@show'])->where('parameters', '(.*)');

Route::resource('recipes', 'RecipeController');

Route::get('r/{token}', ['as' => 'r.show_token', 'uses' => 'RecipeController@show', function($token) {}])->where(['token' => '[A-Za-z0-9]+']);

Route::resource('occasions', 'OccasionController');
