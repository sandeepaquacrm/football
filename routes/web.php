<?php

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

Route::get('/', function () {
    return view('welcome');
});


Route::post('/login','ApiController@login');

Route::post('/signup','ApiController@signup');

Route::post('/avatar_change','ApiController@avatar_change');

Route::post('/language_change','ApiController@language_change');

Route::post('/get_user_detail','ApiController@get_user_detail');

Route::post('/get_faq','ApiController@get_faq');

Route::get('/get_language','ApiController@get_language');
