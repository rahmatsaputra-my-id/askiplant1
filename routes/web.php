<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'PIrejectionController@index');
Route::get('/table', 'PIrejectionController@table');
Route::POST('/getTableData', 'PIrejectionController@getTableData');
Route::POST('/getTableData2', 'PIrejectionController@getTableData2');
Route::get('/getRejectionRealtime', 'PIrealtimeController@getRejectionRealtime');
Route::get('/getRejectionTypeRealtime', 'PIrealtimeController@getRejectionTypeRealtime');
Route::get('/getOKRatioRealtime', 'PIrealtimeController@getOKRatioRealtime');
// Route::get('/getRealtime', 'PIrealtimeController@getRealtime');
// Route::get('/getPlanningVsAchievement', 'PIrealtimeController@getPlanningVsAchievement');
// route report rejection
Route::group(['prefix'=>'report','as'=>'report.'], function(){
    Route::get('/rejection', ['as' => 'rejection', 'uses' => 'PIrejectionController@rejection']);
    Route::post('/getRejection', ['as' => 'getRejection', 'uses' => 'PIrejectionController@getRejection']);
    Route::post('/getTableData', ['as' => 'getTableData', 'uses' => 'PIrejectionController@getTableData']);
    Route::post('/getTableData2', ['as' => 'getTableData2', 'uses' => 'PIrejectionController@getTableData2']);
});
// route report achievement
Route::group(['prefix'=>'report','as'=>'report.'], function(){
    Route::get('/achievement', ['as' => 'achievement', 'uses' => 'PIachievementController@achievement']);
    Route::post('/getAchievement', ['as' => 'getAchievement', 'uses' => 'PIachievementController@getAchievement']);
    // Route::post('/getTableData', ['as' => 'getTableData', 'uses' => 'PIrejectionController@getTableData']);
    // Route::post('/getTableData2', ['as' => 'getTableData2', 'uses' => 'PIrejectionController@getTableData2']);
});

// route report realtime
Route::group(['prefix'=>'report','as'=>'report.'], function(){
    Route::get('/realtime', ['as' => 'realtime', 'uses' => 'PIrealtimeController@realtime']);
    // Route::get('/getRealtime', ['as' => 'getRealtime', 'uses' => 'PIrealtimeController@getRealtime']);
    // Route::post('/getTableData', ['as' => 'getTableData', 'uses' => 'PIrejectionController@getTableData']);
    // Route::post('/getTableData2', ['as' => 'getTableData2', 'uses' => 'PIrejectionController@getTableData2']);
});
