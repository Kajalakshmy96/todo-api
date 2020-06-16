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
        // /api/v1/task/
        Route::post('', 'TaskController@getTasks');
        // /api/v1/task/{id}
        Route::get('{id}', 'TaskController@getTask');
        // /api/v1/task/create
        Route::post('create', 'TaskController@createTask');
        // /api/v1/task/update/{id}
        Route::post('update/{id}', 'TaskController@updateTask');
        // /api/v1/task/delete/{id}
        Route::post('delete/{id}', 'TaskController@deleteTask');
        // /api/v1/task/state/{id}
        Route::post('state/{id}', 'TaskController@updateState');
    });

    //Summary
    Route::group([
        'prefix' => 'summary'
    ], function () {
        // /api/v1/summary
        Route::post('', 'SummaryController@getSummary');
    });
});
