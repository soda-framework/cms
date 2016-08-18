<?php


Route::get('/auth/logout', ['as' => 'soda_theme_hint.auth.logout', 'uses' => 'Auth\AuthController@logout']);
Route::get('/auth/login', ['as' => 'soda_theme_hint.auth.login', 'uses' => 'Auth\AuthController@loginForm']);
Route::post('/auth/login', ['as' => 'soda_theme_hint.auth.submit_login', 'uses' => 'Auth\AuthController@login']);

Route::get('/restricted', ['as' => 'soda_theme_hint.restricted', 'uses' => 'RestrictedController@index']);
