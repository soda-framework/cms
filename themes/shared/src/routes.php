<?php

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function() {
    Route::get('logout', 'AuthController@logout')->name('soda-example.auth.logout');
    Route::get('login', 'AuthController@loginForm')->name('soda-example.auth.login');
    Route::post('login', 'AuthController@login')->name('soda-example.auth.submit_login');
});

Route::get('restricted', 'RestrictedController@index')->name('soda-example.restricted');
