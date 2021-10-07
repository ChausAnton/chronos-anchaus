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

Route::get('/test', function () {
    return ['message' => 'hello'];
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

///////////
Route::post('/CreateCalendar', 'App\Http\Controllers\CalendarController@CreateCalendar');
Route::get('/getCalendarsForUser', 'App\Http\Controllers\CalendarController@getCalendarsForUser');
Route::get('/getCalendar/{id}', 'App\Http\Controllers\CalendarController@getCalendar');
Route::delete('/DeleteCalendar/{id}', 'App\Http\Controllers\CalendarController@DeleteCalendar');


////////
Route::get('/getEventForCalendar/{id}/{eventId}', 'App\Http\Controllers\EventController@getEventForCalendar');
Route::get('/getEventsForCalendar/{id}', 'App\Http\Controllers\EventController@getEventsForCalendar');
Route::post('/createEventForCalendar/{id}', 'App\Http\Controllers\EventController@createEventForCalendar');
Route::delete('/DeleteEventFromCalendar/{id}/{eventId}', 'App\Http\Controllers\EventController@DeleteEventFromCalendar');


Route::post('/login', 'App\Http\Controllers\AuthController@Login');
Route::post('/register', 'App\Http\Controllers\AuthController@register');
Route::get('/logout', 'App\Http\Controllers\AuthController@Logout');
Route::post('/requestForPasswordReset', 'App\Http\Controllers\AuthController@requestForPasswordReset');
Route::post('/reset/{token}/{userId}', 'App\Http\Controllers\AuthController@PasswordReset');





