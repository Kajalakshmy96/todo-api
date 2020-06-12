<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group([
    'prefix' => 'v1',
    'middleware' => 'auth:api'
], function () {

    //Task
    Route::group([
        'prefix' => 'task'
    ], function () {
        Route::post('', 'TaskController@getTasks');
        Route::get('{id}', 'TaskController@getTask');
        Route::post('create', 'TaskController@createTask');
        Route::post('update/{id}', 'TaskController@updateTask');
        Route::post('delete/{id}', 'TaskController@deleteTask');
        Route::post('state/{id}', 'TaskController@updateState');
    });

    //Summary
    Route::group([
        'prefix' => 'summary'
    ], function () {
        Route::post('', 'SummaryController@getSummary');
    });
});
