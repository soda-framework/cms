<?php

Route::group(['prefix' => config('soda.cms.path'), 'middleware' => ['web']], function () {
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        // Authentication routes
        Route::get('login', 'LoginController@showLoginForm')->name('soda.login');
        Route::post('login', 'LoginController@login')->name('soda.login-attempt');
        Route::get('logout', 'LoginController@logout')->name('soda.logout');
    });

    // Dashboard and user routes...
    Route::group(['middleware' => 'soda.auth'], function () {
        Route::post('reset-password', 'HomeController@resetWeakPassword')->name('soda.reset-weak-password');
        Route::get('/', 'HomeController@getIndex')->name('soda.home')->middleware('soda.permission:access-cms');
        Route::post('sort', 'Api\SortController@sort')->name('soda.sort')->middleware('soda.permission:access-cms');
        Route::get('toggle-draft', 'HomeController@getToggleDraft')->name('soda.toggle-draft')->middleware('soda.permission:view-drafts');

        Route::get('settings', 'SettingsController@index')->name('soda.settings.index')->middleware('soda.permission:view-applications');
        Route::group(['prefix' => 'settings'], function () {
            Route::get('edit/{id?}', 'SettingsController@edit')->name('soda.settings.edit');
            Route::put('edit/{id?}', 'SettingsController@update')->name('soda.settings.update');
        });

        Route::resource('pages', 'PageController', [
            'as'     => 'soda',
            'except' => 'show',
        ]);

        Route::post('page-types/{pageTypeId}/block-types/attach', 'PageTypeBlockController@attach')->name('soda.page-types.blocks.attach');
        Route::delete('page-types/{pageTypeId}/block-types/{blockTypeId}/detach', 'PageTypeBlockController@detach')->name('soda.page-types.blocks.detach');
        Route::post('page-types/{pageTypeId}/fields/attach', 'PageTypeFieldController@attach')->name('soda.page-types.fields.attach');
        Route::delete('page-types/{pageTypeId}/fields/{fieldId}/detach', 'PageTypeFieldController@detach')->name('soda.page-types.fields.detach');
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

        Route::post('block-types/{blockTypeId}/fields/attach', 'BlockTypeFieldController@attach')->name('soda.block-types.fields.attach');
        Route::delete('block-types/{blockTypeId}/fields/{fieldId}/detach', 'BlockTypeFieldController@detach')->name('soda.block-types.fields.detach');
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

        Route::group(['prefix' => 'upload'], function () {
            Route::post('/', 'UploadController@upload')->name('soda.upload');
            Route::post('delete', 'UploadController@delete')->name('soda.upload.delete');
        });
    });
});

Route::any('{slug?}', function () {
    // Caught by event
})->where('slug', '^(?!_).+')->name('soda.page.match');
