<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

//View for Upload Image
Route::get('upload', function() {
  	return View::make('upload');
});

//Uploads image
Route::post('users/upload', 'UsersController@upload');

//Retrieving the uploaded Image
Route::get('retrieveImage', 'UsersController@retrieveImage');

//Edit Profile
Route::get('edit', 'UsersController@edit');

Route::post('update', 'UsersController@update');

//Edit Password
Route::get('edtPass', function() {
	return View::make('editPass');
});

Route::post('users/editPass', 'UsersController@editPass');
//Edit Image
Route::get('updateImg', function() {
  	return View::make('editImg');
});

Route::post('image', 'UsersController@image');

// JSON
Route::get('users/profile', function(){
	return Response::json([Confide::user()]);
});

Route::get('json', 'UsersController@json');

// Confide routes
Route::get('users/create', 'UsersController@create');
Route::post('users', 'UsersController@store');
Route::get('users/login', 'UsersController@login');
Route::post('users/login', 'UsersController@doLogin');
Route::get('users/confirm/{code}', 'UsersController@confirm');																																																		
Route::get('users/forgot_password', 'UsersController@forgotPassword');
Route::post('users/forgot_password', 'UsersController@doForgotPassword');
Route::get('users/reset_password/{token}', 'UsersController@resetPassword');
Route::post('users/reset_password', 'UsersController@doResetPassword');
Route::get('users/logout', 'UsersController@logout');
Route::get('userpanel/dashboard', function(){ 
	return View::make('userpanel.dashboard'); 
});
