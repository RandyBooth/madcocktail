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
//Route::get('ingredients/{one}/{two?}/{three?}/{four?}/{five?}', ['as' => 'ingredients.show', 'uses' => 'IngredientController@show']);
Route::get('ingredients/{parameters?}', ['as' => 'ingredients.show', 'uses' => 'IngredientController@show'])->where('parameters', '(.*)');



Route::get('r/{token}', ['as' => 'r.show_token', 'uses' => 'RecipeController@show', function($token) {

}])->where(['token' => '[A-Za-z0-9]+']);
Route::resource('recipes', 'RecipeController');

Route::resource('occasions', 'OccasionController');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
