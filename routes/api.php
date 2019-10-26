<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/* Task routes */
Route::get('v1/tasks', 'Api\TaskApiController@index');
Route::post('v1/tasks', 'Api\TaskApiController@store');
Route::get('v1/tasks/{id}', 'Api\TaskApiController@show');
Route::put('v1/tasks/{id}', 'Api\TaskApiController@update');
Route::post('v1/delete/task', 'Api\TaskApiController@destroy');
Route::post('v1/update/timer', 'Api\TaskApiController@updateTimer');
Route::post('v1/task/finished', 'Api\TaskApiController@setFinished');
/* Auth routes */
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('/v1/users', 'Api\HomeApiController@getUsers');
    Route::get('/v1/get/tasks', 'Api\HomeApiController@getUserTasks');
    Route::get('/v1/get/finished/tasks', 'Api\HomeApiController@getFinishedTasks');
    /*** Todo routes **/
    Route::get('/v1/todos', 'Api\TodoApiController@index');
    Route::post('/v1/todos', 'Api\TodoApiController@store');
    Route::get('/v1/todo/{id}', 'Api\TodoApiController@show');
    Route::post('/v1/todo/{id}', 'Api\TodoApiController@update');
    Route::post('/v1/todos/delete', 'Api\TodoApiController@destroy');
    /*** Reports routes **/
    Route::get('/v1/reports', 'Api\TaskApiController@getReports');
});
