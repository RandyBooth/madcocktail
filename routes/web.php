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

Route::group(['middleware' => ['admin', 'isVerified'], 'prefix' => 'admin'], function () {
    Route::group(['prefix' => 'ingredients'], function () {
        Route::get('tree', 'IngredientController@tree');
    });
});

Auth::routes();

Route::get('email/confirm/error', 'Auth\RegisterController@getVerificationError')->name('email-verification.error');
Route::get('email/confirm/check/{token}', 'Auth\RegisterController@getVerification')->name('email-verification.check');

Route::group(['prefix' => 'login'], function() {
    Route::group(['prefix' => '{provider}', 'where' => ['provider' => '[a-z]+']], function() {
        Route::get('/', ['as' => 'oauth.redirect', 'uses' => 'OAuthController@redirect']);
        Route::get('callback', ['as' => 'oauth.callback', 'uses' => 'OAuthController@callback']);
    });
});

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::get('profile', function() {
    echo 'In work';
})->name('profile');

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
