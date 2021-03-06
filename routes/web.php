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

 Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

 //重设密码
 Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

 Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

 Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

 Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update'); 

 //微博
 Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);

 //关注
 Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');//显示用户的关注人
 Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');//显示用户的粉丝列表

 //加关注，取消关注
 Route::post('/users/followers/{user}', 'FollowersController@store')->name('followers.store');
 Route::delete('/users/followers/{user}', 'FollowersController@destroy')->name('followers.destroy');
