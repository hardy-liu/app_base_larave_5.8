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

//if (env('APP_ENV') === 'local') {
//    auth()->loginUsingId(100000);
//}

Route::group([
    'prefix' => 'api',
    'namespace' => 'User'
], function () {
    //no need login
    Route::get('is-login', 'Auth\LoginController@isLogin');
    Route::get('captcha', 'CaptchaController@showCaptcha');
    Route::post('login', 'Auth\LoginController@login');   //登陆
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');   //登出

    //need login
    Route::group([
        'middleware' => ['auth:web'],
    ], function () {
        Route::get('user/info', 'UserController@getUserInfo');

        //调试用接口
//        if (env('APP_ENV') === 'local') {
//            Route::post('user/test', 'TestController@test');
//        }
    });
});
