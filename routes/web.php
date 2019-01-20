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

 Route::get('/','StaticPagesController@home')->name('home');

 Route::get('/help','StaticPagesController@help')->name('help');

 Route::get('/about','StaticPagesController@about')->name('about');
 
 
 Route::get('signup','UsersController@create')->name('signup');
 
 Route::resource('users','UsersController');

 //会话
 Route::get('login', 'SessionController@create')->name('login');//显示登录页面

 Route::post('login', 'SessionController@store')->name('login');//创建新建会话（登录）

 Route::delete('logout', 'SessionController@destroy')->name('logout');//销毁会话（退出登录）
  
