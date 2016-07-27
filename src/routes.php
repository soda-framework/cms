<?php
Route::group(['middleware'=>'web'], function(){
	Route::group(['prefix' => config('soda.cms_path')], function () {
//	Route::auth();
//	Route::get('dash', function(){
//		dd('dashboard');
//	});

// Authentication routes
		Route::get('auth/login', ['as'=>'login', 'uses'=>'Auth\AuthController@getLogin']);
		Route::post('auth/login', ['as'=>'login-attempt', 'uses'=>'Auth\AuthController@postLogin']);
		Route::get('auth/logout', ['as'=>'logout', 'uses'=>'Auth\AuthController@getLogout']);

// Registration routes...
		Route::get('auth/register', ['as'=>'register', 'uses'=>'Auth\AuthController@getRegister']);
		Route::post('auth/register', ['as'=>'register-attempt', 'uses'=>'Auth\AuthController@postRegister']);

// Dashboard and user routes...
		Route::group(['middleware' => 'soda.auth:soda'], function () {
			Route::get('/', ['as'=>'home', 'uses'=>'HomeController@getIndex']);

			//test routes..
			Route::get('/pages/makeroot/{id?}', ['as'=>'page-makeroot', 'uses'=>'PageController@getMakeRoot']);



			Route::get('/pages', ['as'=>'soda.pages', 'uses'=>'PageController@getIndex']);
			Route::get('/pages/view/{id?}', ['as'=>'soda.pages.view', 'uses'=>'PageController@view']);
			Route::post('/pages/edit/{id?}', ['as'=>'soda.pages.edit', 'uses'=>'PageController@edit']);
			Route::get('/pages/delete/{id?}', ['as'=>'soda.pages.delete', 'uses'=>'PageController@delete']);
			Route::get('/pages/move/{id}/{parent_id}/{position}', ['as'=>'soda.pages.move', 'uses'=>'PageController@move']);
			Route::get('/pages/create/{id?}', ['as'=>'soda.pages.create', 'uses'=>'PageController@createForm']);
			Route::post('/pages/create/{parent_id?}', ['as'=>'soda.pages.create', 'uses'=>'PageController@create']);

			Route::get('/templates', ['as'=>'soda.templates', 'uses'=>'TemplateController@getIndex']);
			Route::get('/templates/view/{id?}', ['as'=>'soda.templates.view', 'uses'=>'TemplateController@view']);
			Route::post('/templates/edit/{id?}', ['as'=>'soda.templates.edit', 'uses'=>'TemplateController@edit']);
			Route::get('/templates/delete/{id?}', ['as'=>'soda.templates.delete', 'uses'=>'TemplateController@delete']);
			Route::get('/templates/move/{id}/{parent_id}/{position}', ['as'=>'soda.templates.move', 'uses'=>'TemplateController@move']);
			Route::get('/templates/create/{id?}', ['as'=>'soda.templates.create', 'uses'=>'TemplateController@createForm']);
			Route::post('/templates/create/{parent_id?}', ['as'=>'soda.templates.create', 'uses'=>'TemplateController@create']);


			Route::get('/fields', ['as'=>'soda.field', 'uses'=>'FieldController@index']);
			Route::get('/fields/view/{id?}', ['as'=>'soda.field.view', 'uses'=>'FieldController@view']);
			Route::post('/fields/view/{id?}', ['as'=>'soda.field.edit', 'uses'=>'FieldController@edit']);
			Route::get('/fields/view/', ['as'=>'soda.field.create', 'uses'=>'FieldController@edit']);
			Route::get('/fields/delete/{id?}', ['as'=>'soda.field.delete', 'uses'=>'FieldController@delete']);

			Route::get('/blocks', ['as'=>'soda.block', 'uses'=>'BlockController@index']);
			Route::get('/blocks/view/{id?}', ['as'=>'soda.block.view', 'uses'=>'BlockController@view']);
			Route::get('/blocks/delete/{id?}', ['as'=>'soda.block.delete', 'uses'=>'BlockController@delete']);
			Route::get('/blocks/view/', ['as'=>'soda.block.create', 'uses'=>'BlockController@edit']);
			Route::post('/blocks/view/{id?}', ['as'=>'soda.block.edit', 'uses'=>'BlockController@edit']);

			//routes for crud on the dynamically added tables
			Route::get('/dyn/{type}', ['as'=>'soda.dyn', 'uses'=>'DynamicController@index']);
			Route::get('/dyn/{type}/view/{id?}', ['as'=>'soda.dyn.view', 'uses'=>'DynamicController@view']);
			Route::post('/dyn/{type}/view/{id?}', ['as'=>'soda.dyn.edit', 'uses'=>'DynamicController@edit']);
			Route::post('/dyn/{type}/inline/{id}/{field}', ['as'=>'soda.dyn.inline.edit', 'uses'=>'DynamicController@inlineEdit']);
			Route::get('/dyn/{type}/delete/{id?}', ['as'=>'soda.dyn.delete', 'uses'=>'DynamicController@delete']);
			//update the data on this block type
			//Route::get('/dyn/{type}/update/{id?}', ['as'=>'soda.dyn.update', 'uses'=>'DynamicController@update']);

			Route::get('/block-types', ['as'=>'soda.block_type', 'uses'=>'BlockTypeController@index']);
			Route::get('/block-types/view/{id?}', ['as'=>'soda.block_type.view', 'uses'=>'BlockTypeController@view']);
			Route::get('/block-types/delete/{id?}', ['as'=>'soda.block_type.delete', 'uses'=>'BlockTypeController@delete']);
			Route::post('/block-types/view/{id?}', ['as'=>'soda.block_type.edit', 'uses'=>'BlockTypeController@edit']);

			Route::get('/page-types', ['as'=>'soda.page_type', 'uses'=>'PageTypeController@index']);
			Route::get('/page-types/view/{id?}', ['as'=>'soda.page_type.view', 'uses'=>'PageTypeController@view']);
			Route::get('/page-types/delete/{id?}', ['as'=>'soda.page_type.delete', 'uses'=>'PageTypeController@delete']);
			Route::post('/page-types/view/{id?}', ['as'=>'soda.page_type.edit', 'uses'=>'PageTypeController@edit']);

			Route::get('/users', ['as'=>'soda.user', 'uses'=>'UserController@index']);
			Route::get('/users/view/{id?}', ['as'=>'soda.user.view', 'uses'=>'UserController@view']);
			Route::get('/users/view/', ['as'=>'soda.user.create', 'uses'=>'UserController@view']);
			Route::post('/users/view/{id?}', ['as'=>'soda.user.edit', 'uses'=>'UserController@edit']);
			Route::get('/users/delete/{id?}', ['as'=>'soda.user.delete', 'uses'=>'UserController@delete']);



			Route::get('/tree.json', ['as'=>'soda.tree.json', 'uses'=>'TreeController@jsonTree']);

			Route::get('/tree', ['as'=>'soda.tree', 'uses'=>'TreeController@htmlTree']);


			Route::post('/upload',['as'=>'soda.upload', 'uses'=>'UploadController@upload']);
			Route::post('/upload/delete',['as'=>'soda.upload.delete', 'uses'=>'UploadController@delete']);

			//Pulls in upload urls where we can parse them with something.
			Route::get('/upload/retrieve',['as'=>'soda.upload.retrieve', 'uses'=>'UploadController@retrieveUpload']);

			//
			//Route::get('/users', function(Soda\Models\User $user){
			//	return $user;
			//});
			//
			//Route::get('/users/{user}', function(Soda\Models\User $user){
			//	return $user;
			//});
		});
	});

	Route::any('/{slug?}', function ($slug = '/') {
		return Soda\Controllers\PageController::page($slug);
	})->where('slug', '(.*)');
});



