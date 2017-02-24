<?php

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function() {
    Route::get('logout', 'LoginController@logout')->name('soda-example.auth.logout');
    Route::get('login', 'LoginController@showLoginForm')->name('soda-example.auth.login');
    Route::post('login', 'LoginController@login')->name('soda-example.auth.login.post');
});

Route::get('/', 'HomeController@index')->name('soda-example.index');
Route::get('restricted', 'RestrictedController@index')->name('soda-example.restricted');
