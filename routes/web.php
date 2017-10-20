<?php

Route::group(['prefix' => config('soda.cms.path'), 'middleware' => ['web']], function () {
    Route::post('set-language', 'HomeController@setLanguage')->name('soda.language');

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
        Route::get('toggle-draft', 'HomeController@toggleDraft')->name('soda.toggle-draft')->middleware('soda.permission:view-drafts');
        Route::post('add-quicklink', 'HomeController@addQuicklink')->name('soda.add-quicklink');

        Route::get('settings', 'SettingsController@index')->name('soda.settings.index')->middleware('soda.permission:view-applications');
        Route::group(['prefix' => 'settings'], function () {
            Route::get('edit/{id?}', 'SettingsController@edit')->name('soda.settings.edit');
            Route::put('edit/{id?}', 'SettingsController@update')->name('soda.settings.update');
        });

        Route::resource('content', 'ContentController', [
            'as' => 'soda',
        ]);

        Route::post('content-types/{contentTypeId}/block-types/attach', 'ContentTypeBlockController@attach')->name('soda.content-types.blocks.attach');
        Route::delete('content-types/{contentTypeId}/block-types/{blockTypeId}/detach', 'ContentTypeBlockController@detach')->name('soda.content-types.blocks.detach');
        Route::post('content-types/{contentTypeId}/fields/attach', 'ContentTypeFieldController@attach')->name('soda.content-types.fields.attach');
        Route::delete('content-types/{contentTypeId}/fields/{fieldId}/detach', 'ContentTypeFieldController@detach')->name('soda.content-types.fields.detach');
        Route::resource('content-types', 'ContentTypeController', [
            'as'     => 'soda',
            'except' => 'show',
        ]);

        Route::post('content/{contentId}/block-types/attach', 'ContentBlockController@attach')->name('soda.content.blocks.attach');
        Route::delete('content/{contentId}/block-types/{blockTypeId}/detach', 'ContentBlockController@detach')->name('soda.content.blocks.detach');
        Route::resource('content.block-types.block', 'ContentBlockController', [
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
            Route::post('/mce', 'UploadController@mceUpload')->name('soda.mce-upload');
            Route::post('delete', 'UploadController@delete')->name('soda.upload.delete');
        });
    });
});

Route::any('{slug?}', function () {
    // Caught by event
})->where('slug', '^(?!_).+')->name('soda.page.match');
