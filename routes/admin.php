<?php

//管理员后台接口
Route::group([
    'prefix' => 'api',
    'namespace' => 'Admin',
], function () {
    //不需要登陆访问的接口
    Route::get('is-login', 'Auth\LoginController@isLogin');
    Route::get('captcha', 'CaptchaController@showCaptcha');
    Route::post('login', 'Auth\LoginController@login');   //登陆
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');   //登出

    //需要登陆访问的接口
    Route::group([
        'middleware' => ['auth:web-admin'],
    ], function () {
        Route::get('admin/info', 'AdminController@getInfo');

    });

    //test debug api
    Route::group([
        'prefix' => 'debug',
    ], function () {
        //test debug api
    });
});
