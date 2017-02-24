<?php

Route::group(['prefix' => config('soda.cms.path'), 'middleware' => ['web']], function() {
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function() {
        // Authentication routes
        Route::get('login', 'LoginController@showLoginForm')->name('soda.login');
        Route::post('login', 'LoginController@login')->name('soda.login-attempt');
        Route::get('logout', 'LoginController@logout')->name('soda.logout');
    });

    // Dashboard and user routes...
    Route::group(['middleware' => 'soda.auth'], function() {
        Route::get('/', 'HomeController@getIndex')->name('soda.home')->middleware('soda.permission:access-cms');
        Route::post('sort', 'Api\SortController@sort')->name('soda.sort')->middleware('soda.permission:access-cms');
        Route::get('toggle-draft', 'HomeController@getToggleDraft')->name('soda.toggle-draft')->middleware('soda.permission:view-drafts');

        Route::get('applications', 'ApplicationController@index')->name('soda.application.index')->middleware('soda.permission:view-applications');
        Route::group(['prefix' => 'application'], function() {
            Route::get('edit/{id?}', 'ApplicationController@edit')->name('soda.application.edit');
            Route::put('edit/{id?}', 'ApplicationController@update')->name('soda.application.update');
        });

        Route::resource('pages', 'PageController', [
            'as'     => 'soda',
            'except' => 'show',
        ]);

        Route::post('page-types/{pageTypeId}/block-types/attach', 'PageTypeBlockController@attach')->name('soda.page-types.blocks.attach');
        Route::delete('page-types/{pageTypeId}/block-types/{blockTypeId}/detach', 'PageTypeBlockController@detach')->name('soda.page-types.blocks.detach');
        Route::resource('page-types', 'PageTypeController', [
            'as'     => 'soda',
            'except' => 'show',
        ]);

        Route::post('pages/{pageId}/block-types/attach', 'PageBlockController@attach')->name('soda.pages.blocks.attach');
        Route::delete('pages/{pageId}/block-types/{blockTypeId}/detach', 'PageBlockController@detach')->name('soda.pages.blocks.detach');
        Route::resource('pages.block-types.block', 'PageBlockController', [
            'as'     => 'soda',
            'except' => 'show',
        ]);

        Route::resource('block-types', 'BlockTypeController', [
            'as'     => 'soda',
            'except' => 'show',
        ]);

        Route::resource('fields', 'FieldController', [
            'as'     => 'soda',
            'except' => 'show',
        ]);

        Route::resource('users', 'UserController', [
            'as'     => 'soda',
            'except' => 'show',
        ]);

        Route::resource('roles', 'RoleController', [
            'as'     => 'soda',
            'except' => 'show',
        ]);

        Route::resource('permissions', 'PermissionController', [
            'as'     => 'soda',
            'except' => 'show',
        ]);

        Route::group(['prefix' => 'upload'], function() {
            Route::post('/', 'UploadController@upload')->name('soda.upload');
            Route::post('delete', 'UploadController@delete')->name('soda.upload.delete');
        });
    });
});

Route::any('{slug?}', function() {
    // Caught by event
})->where('slug', '^(?!_).+')->name('soda.page.match');
