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

Route::group(['middleware' => 'fw-block-bl'], function () {
    Auth::routes();

    Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'isVerified', 'user-valid']], function () {
        Route::group(['prefix' => 'ingredients'], function () {
            Route::get('tree', 'IngredientController@tree');
            Route::get('pending', 'IngredientController@pending');
        });

        Route::group(['prefix' => 'recipes'], function () {
            Route::get('lists', 'RecipeController@admin_lists');
        });

        Route::group(['prefix' => 'clear'], function () {
            Route::get('all', 'ClearCacheController@all');
            Route::get('glasses', 'ClearCacheController@glasses');
            Route::get('measures', 'ClearCacheController@measures');
        });
    });

    Route::get('{path}', function($filename) {
        return Bust::css($filename);
    })->where('path', '.*\.css$');
    App::make('cachebuster.StripSessionCookiesFilter')->addPattern('|\.css$|');

    Route::get('me', ['as' => 'user-profile.index', 'uses' => 'UserProfileController@index']);
    Route::get('me/{username}', ['as' => 'user-profile.show', 'uses' => 'UserProfileController@show']);

    Route::group(['prefix' => 'settings'], function() {
        Route::get('/', ['as' => 'user-settings.index.edit', 'uses' => 'UserSettingController@indexEdit']);
        Route::put('/', ['as' => 'user-settings.index.update', 'uses' => 'UserSettingController@indexUpdate']);

        Route::get('email', ['as' => 'user-settings.email.edit', 'uses' => 'UserSettingController@emailEdit']);
        Route::put('email', ['as' => 'user-settings.email.update', 'uses' => 'UserSettingController@emailUpdate']);

        Route::get('password', ['as' => 'user-settings.password.edit', 'uses' => 'UserSettingController@passwordEdit']);
        Route::put('password', ['as' => 'user-settings.password.update', 'uses' => 'UserSettingController@passwordUpdate']);

        Route::get('profile', ['as' => 'user-settings.profile.edit', 'uses' => 'UserSettingController@profileEdit']);
        Route::put('profile', ['as' => 'user-settings.profile.update', 'uses' => 'UserSettingController@profileUpdate']);
    });

    Route::get('email/error', ['as' => 'email-verification.error', 'uses' => 'Auth\RegisterController@getVerificationError']);
    Route::get('email/activate/{token}', ['as' => 'email-verification.check', 'uses' => 'Auth\RegisterController@getVerification', 'middleware' => ['throttle:10,10', 'clearUser']]);
    Route::get('email/resend', ['as' => 'email-verification.resend', 'uses' => 'UserSettingController@resendVerification', 'middleware' => 'throttle:5,10']);

    Route::group(['prefix' => 'login'], function() {
        Route::group(['prefix' => '{provider}', 'where' => ['provider' => '[a-z]+']], function() {
            Route::get('/', ['as' => 'oauth.redirect', 'uses' => 'OAuthController@redirect']);
            Route::get('callback', ['as' => 'oauth.callback', 'uses' => 'OAuthController@callback']);
        });
    });

    Route::get('/', ['as' => 'home', 'uses' => 'RecipeController@home']);

    Route::resource('ingredients', 'IngredientController', [
        'except' => [
            'index',
            'show'
        ],
        'parameters' => [
            'ingredients' => 'token'
        ]
    ]);

    Route::get('ingredients', ['as' => 'ingredients.index', 'uses' => 'IngredientController@show']);
    Route::get('ingredients/{parameters?}', ['as' => 'ingredients.show', 'uses' => 'IngredientController@show'])->where('parameters', '(.*)');

    Route::resource('recipes', 'RecipeController', ['except' => ['show']]);
    Route::get('recipes/{token}/{slug?}', ['as' => 'recipes.show', 'uses' => 'RecipeController@show'])->where('token', '[a-zA-Z0-9-]+')->where('slug', '[a-z0-9-]+');

    Route::group(['prefix' => 'ajax'], function() {
        Route::post('search/{type?}', ['before' => 'csrf', 'middleware' => 'throttle:30,5', 'as' => 'search-ajax', 'uses' => 'SearchController@ajax'])->where('type', '[A-Za-z]+');

        Route::post('recipe-image', ['before' => 'csrf', 'middleware' => 'throttle:15,5', 'as' => 'ajax_recipe_image', 'uses' => 'RecipeImageController@store']);

        Route::post('recipe-favorite', ['before' => 'csrf', 'middleware' => 'throttle:50,5', 'as' => 'ajax_recipe_favorite', 'uses' => 'UserFavoriteRecipeController@store']);
    });

    Route::post('search/{type?}', ['middleware' => 'throttle:30,5', 'as' => 'search', 'uses' => 'SearchController@search'])->where('type', '[A-Za-z]+');

//    Route::resource('occasions', 'OccasionController');

    Route::get('privacy', function() {echo 'Coming soon - Privacy Policy';});
    Route::get('terms', function() {echo 'Coming soon - Terms of Service';});
});
