<?php

use Illuminate\Http\Request;

Route::post('/v1/users', 'API\UsersController@create');
Route::group(['middleware' => 'auth:api','prefix'=>'v1'], function () {
    Route::group(['prefix'=>'users'], function () {
        Route::get('/', 'API\UsersController@index');
        Route::get('/{id}', 'API\UsersController@show');
        Route::post('/{id}', 'API\UsersController@update');
        Route::delete('/{id}', 'API\UsersController@delete');
    });

    Route::group(['prefix'=>'posts'], function () {
    	Route::post('/', 'API\PostsController@create');
        Route::get('/', 'API\PostsController@index');
        Route::get('/{id}', 'API\PostsController@show');
        Route::put('/{id}', 'API\PostsController@update');
        Route::delete('/{id}', 'API\PostsController@delete');
    });

    Route::group(['prefix'=>'comments'], function () {
        Route::post('/', 'API\CommentsController@create');
        Route::get('/', 'API\CommentsController@index');
        Route::get('/{id}', 'API\CommentsController@show');
        Route::put('/{id}', 'API\CommentsController@update');
        Route::delete('/{id}', 'API\CommentsController@delete');
    });
});

Route::group(['prefix'=>'v1'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('profile', 'AuthController@me');
});