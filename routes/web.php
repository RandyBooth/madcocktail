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

Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'isVerified']], function () {
    Route::group(['prefix' => 'ingredients'], function () {
        Route::get('tree', 'IngredientController@tree');
    });
});

Route::group(['prefix' => 'settings'], function() {
    Route::get('/', ['as' => 'user-settings.index', 'uses' => 'UserSettingController@index']);
    Route::get('email', ['as' => 'user-settings.email.edit', 'uses' => 'UserSettingController@emailEdit']);
    Route::put('email', ['as' => 'user-settings.email.update', 'uses' => 'UserSettingController@emailUpdate']);
    Route::get('password', ['as' => 'user-settings.password.edit', 'uses' => 'UserSettingController@passwordEdit']);
    Route::put('password', ['as' => 'user-settings.password.update', 'uses' => 'UserSettingController@passwordUpdate']);
});

Route::get('email/error', 'Auth\RegisterController@getVerificationError')->name('email-verification.error');
Route::get('email/activate/{token}', 'Auth\RegisterController@getVerification')->name('email-verification.check');

Route::group(['prefix' => 'login'], function() {
    Route::group(['prefix' => '{provider}', 'where' => ['provider' => '[a-z]+']], function() {
        Route::get('/', ['as' => 'oauth.redirect', 'uses' => 'OAuthController@redirect']);
        Route::get('callback', ['as' => 'oauth.callback', 'uses' => 'OAuthController@callback']);
    });
});

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::get('search/{type?}', ['before' => 'csrf', 'middleware' => 'throttle:20,1', 'as' => 'search', 'uses' => 'SearchController@search']);
Route::post('search/{type?}', ['before' => 'csrf', 'middleware' => 'throttle:25,1', 'as' => 'search', 'uses' => 'SearchController@search']);

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

Route::group(['prefix' => 'ajax'], function() {
    Route::post('recipe-image', ['before' => 'csrf', 'middleware' => 'throttle:15,1', 'as' => 'ajax_recipe_image', 'uses' => 'RecipeImageController@store']);
    Route::delete('recipe-image', ['before' => 'csrf', 'middleware' => 'throttle:15,1', 'as' => 'ajax_recipe_image_destroy', 'uses' => 'RecipeImageController@destroy']);
});

Route::resource('occasions', 'OccasionController');
