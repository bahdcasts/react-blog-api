<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$api = $app->make(Dingo\Api\Routing\Router::class);

$api->version('v1', function ($api) {
    $api->post('/auth/login', [
        'as' => 'api.auth.login',
        'uses' => 'App\Http\Controllers\Auth\AuthController@postLogin',
    ]);

    $api->post('/auth/register', [
        'as' => 'api.auth.register',
        'uses' => 'App\Http\Controllers\Auth\AuthController@postRegister',
    ]);

    $api->get('/articles', [
        'uses' => 'App\Http\Controllers\ArticlesController@index',
        'as' => 'api.articles.index'
    ]);

    $api->get('/articles/category/{id}', [
        'uses' => 'App\Http\Controllers\CategoriesController@show',
        'as' => 'api.categories.show'
    ]);

    $api->get('/articles/{id}', [
        'uses' => 'App\Http\Controllers\ArticlesController@show',
        'as' => 'api.articles.show'
    ]);


    $api->group([
        'middleware' => 'api.auth',
    ], function ($api) {

        $api->post('/articles', [
            'uses' => 'App\Http\Controllers\ArticlesController@store',
            'as' => 'api.articles.store'
        ]);

        $api->patch('/articles/{id}', [
            'uses' => 'App\Http\Controllers\ArticlesController@update',
            'as' => 'api.articles.update'
        ]);

        $api->delete('/articles/{id}', [
            'uses' => 'App\Http\Controllers\ArticlesController@destroy',
            'as' => 'api.articles.destroy'
        ]);

        $api->get('/', [
            'uses' => 'App\Http\Controllers\APIController@getIndex',
            'as' => 'api.index'
        ]);
        $api->get('/auth/user', [
            'uses' => 'App\Http\Controllers\Auth\AuthController@getUser',
            'as' => 'api.auth.user'
        ]);
        $api->patch('/auth/refresh', [
            'uses' => 'App\Http\Controllers\Auth\AuthController@patchRefresh',
            'as' => 'api.auth.refresh'
        ]);
        $api->delete('/auth/invalidate', [
            'uses' => 'App\Http\Controllers\Auth\AuthController@deleteInvalidate',
            'as' => 'api.auth.invalidate'
        ]);
    });
});
