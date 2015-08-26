<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::pattern('member_id', '[0-9]+');

Route::group(['prefix' => 'api/v1','middleware' => 'check'],function()
  {
      Route::post('auth/login', 'LoginController@post_login');
      Route::get('auth/logout', 'LoginController@get_logout');
      Route::post('auth/register', 'LoginController@post_register');
      Route::post('auth/forgot_password', 'LoginController@get_forget_password');
      Route::post('auth/change_password', 'LoginController@post_change_password');
      //User requests
      Route::get('user/{member_id}', 'MemberController@get_user_detail');
      Route::post('user/{member_id}/update', 'MemberController@post_update');
      Route::get('user/{member_id}/event', 'MemberController@get_event_list');

  });