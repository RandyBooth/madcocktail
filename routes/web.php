<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'fw-allow-wl'], function () {
    Auth::routes();

    Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'isVerified']], function () {
        Route::group(['prefix' => 'ingredients'], function () {
            Route::get('tree', 'IngredientController@tree');
        });
    });

    Route::group(['prefix' => 'settings'], function() {
        Route::get('/', ['as' => 'user-settings.index', 'uses' => 'UserSettingController@index']);

        Route::get('username', ['as' => 'user-settings.username.edit', 'uses' => 'UserSettingController@usernameEdit']);
        Route::put('username', ['as' => 'user-settings.username.update', 'uses' => 'UserSettingController@usernameUpdate']);

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

    Route::group(['prefix' => 'ajax'], function() {
    //    Route::get('search/{type?}', ['before' => 'csrf', 'middleware' => 'throttle:20,1', 'as' => 'search', 'uses' => 'SearchController@ajax']);
        Route::post('search/{type?}', ['before' => 'csrf', 'middleware' => 'throttle:25,1', 'as' => 'search-ajax', 'uses' => 'SearchController@ajax']);

        Route::post('recipe-image', ['before' => 'csrf', 'middleware' => 'throttle:15,1', 'as' => 'ajax_recipe_image', 'uses' => 'RecipeImageController@store']);
        Route::delete('recipe-image', ['before' => 'csrf', 'middleware' => 'throttle:15,1', 'as' => 'ajax_recipe_image_destroy', 'uses' => 'RecipeImageController@destroy']);
    });

    Route::post('search/{type?}', ['middleware' => 'throttle:20,1', 'as' => 'search', 'uses' => 'SearchController@search']);

    Route::resource('occasions', 'OccasionController');

    Route::get('privacy', function() {echo 'Coming soon - Privacy Policy';});
    Route::get('terms', function() {echo 'Coming soon - Terms of Service';});
});
