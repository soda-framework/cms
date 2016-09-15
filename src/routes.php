<?php

Route::group(['middleware' => 'web'], function () {
    Route::group(['prefix' => config('soda.cms.path'), 'middleware' => 'soda.main'], function () {
        Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function() {
            // Authentication routes
            Route::get('login', 'AuthController@getLogin')->name('soda.login');
            Route::post('login', 'AuthController@postLogin')->name('soda.login-attempt');
            Route::get('logout', 'AuthController@getLogout')->name('soda.logout');

            // Registration routes...
            Route::get('register', 'AuthController@getRegister')->name('soda.register');
            Route::post('register', 'AuthController@postRegister')->name('soda.register-attempt');
        });

        // Dashboard and user routes...
        Route::group(['middleware' => 'soda.auth:soda'], function () {
            Route::get('/', 'HomeController@getIndex')->name('soda.home');
            Route::get('test', 'HomeController@getTest')->name('soda.test');

            Route::get('toggle-draft', 'HomeController@getToggleDraft')->name("soda.toggle-draft");

            Route::group(['prefix' => 'pages'], function() {
                Route::get('/', 'PageController@getIndex')->name('soda.page');
                Route::get('view/{id?}', 'PageController@view')->name('soda.page.view');
                Route::post('view/{id?}', 'PageController@edit')->name('soda.page.edit');
                Route::get('delete/{id?}', 'PageController@deleteTree')->name('soda.page.delete');
                Route::get('move/{parent_id?}/{id?}/{position?}', 'PageController@move')->name('soda.page.move');
                Route::get('create/{id?}', 'PageController@create')->name('soda.page.create');
                Route::post('create/{parent_id?}', 'PageController@save')->name('soda.page.save');


                Route::get('view/{page_id?}/{type}', 'DynamicController@index')->name('soda.page.block');
                Route::get('view/{page_id?}/{type}/view/{id?}', 'DynamicController@view')->name('soda.page.block.view');
                Route::post('view/{page_id?}/{type}/view/{id?}', 'DynamicController@edit')->name('soda.page.block.edit');
                Route::post('view/{page_id?}/{type}/inline/{id}/{field}', 'DynamicController@inlineEdit')->name('soda.page.block.inline.edit');
                Route::get('view/{page_id?}/{type}/delete/{id?}', 'DynamicController@delete')->name('soda.page.block.delete');

                //test routes..
                //Route::get('/makeroot/{id?}', 'PageController@getMakeRoot')->name('page-makeroot');
            });

            Route::group(['prefix' => 'fields'], function() {
                Route::get('/', 'FieldController@index')->name('soda.field');
                Route::get('view', 'FieldController@view')->name('soda.field.create');
                Route::get('view/{id?}', 'FieldController@view')->name('soda.field.view');
                Route::post('view/{id?}', 'FieldController@edit')->name('soda.field.edit');
                Route::get('delete/{id?}', 'FieldController@delete')->name('soda.field.delete');
            });

            Route::group(['prefix' => 'blocks'], function(){
                Route::get('/', 'BlockController@index')->name('soda.block');
                Route::get('view', 'BlockController@view')->name('soda.block.create');
                Route::get('view/{id?}', 'BlockController@view')->name('soda.block.view');
                Route::post('view/{id?}', 'BlockController@edit')->name('soda.block.edit');
                Route::get('delete/{id?}', 'BlockController@delete')->name('soda.block.delete');
            });

            Route::group(['prefix' => 'block-types'], function() {
                Route::get('/', 'BlockTypeController@index')->name('soda.block_type');
                Route::get('view', 'BlockTypeController@view')->name('soda.block_type.create');
                Route::get('view/{id?}', 'BlockTypeController@view')->name('soda.block_type.view');
                Route::post('view/{id?}', 'BlockTypeController@edit')->name('soda.block_type.edit');
                Route::get('delete/{id?}', 'BlockTypeController@delete')->name('soda.block_type.delete');
            });

            Route::group(['prefix' => 'page-types'], function() {
                Route::get('/', 'PageTypeController@index')->name('soda.page_type');
                Route::get('view', 'PageTypeController@view')->name('soda.page_type.create');
                Route::get('view/{id?}', 'PageTypeController@view')->name('soda.page_type.view');
                Route::post('view/{id?}', 'PageTypeController@edit')->name('soda.page_type.edit');
                Route::get('delete/{id?}', 'PageTypeController@delete')->name('soda.page_type.delete');
            });

            Route::group(['prefix' => 'users'], function() {
                Route::get('/', 'UserController@index')->name('soda.user');
                Route::get('view', 'UserController@view')->name('soda.user.create');
                Route::get('view/{id}', 'UserController@view')->name('soda.user.view');
                Route::post('view/{id?}', 'UserController@edit')->name('soda.user.edit');
                Route::get('delete/{id?}', 'UserController@delete')->name('soda.user.delete');
            });

            Route::group(['prefix' => 'navigation'], function() {
                Route::get('/', 'NavigationController@index')->name('soda.navigation');
                Route::get('view/{id}', 'NavigationController@view')->name('soda.navigation.view');
                Route::post('view/{id?}', 'NavigationController@edit')->name('soda.navigation.edit');
                Route::get('create/{parent_id?}', 'NavigationController@createForm')->name('soda.navigation.create');
                Route::post('create/{parent_id?}', 'NavigationController@create')->name('soda.navigation.create');
                Route::get('delete/{id?}', 'NavigationController@deleteTree')->name('soda.navigation.delete');
                Route::get('move/{parent_id?}/{id?}/{position?}', 'NavigationController@move')->name('soda.navigation.move');
            });

            Route::group(['prefix' => 'upload'], function() {
                Route::post('/', 'UploadController@upload')->name('soda.upload');
                Route::post('delete', 'UploadController@delete')->name('soda.upload.delete');
            });
        });
    });

    Route::any('{slug?}', 'PageController@page')->where('slug', '^(?!_).+')->name('soda.page.match');
});
