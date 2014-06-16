<?php

// basic home:
Route::get('/',['uses' => 'HomeController@index', 'as' => 'index','before' => 'auth.google.reversed']);
Route::get('/home',['uses' => 'HomeController@home', 'as' => 'home','before' => 'auth.google']);
Route::get('/privacy',['uses' => 'HomeController@privacy', 'as' => 'privacy']);

// everything auth related.
Route::post('/login',['uses' => 'AuthController@loginOAuth2', 'as' => 'oauth.form-submit','before' => 'csrf']);
Route::get('/oauth2callback',['uses' => 'AuthController@oauth2callback','as' => 'oauth2callback']);
Route::get('/logout',['uses' => 'AuthController@logout', 'as' => 'oauth.logout','before' => 'auth.google']);
Route::get('/auth',['uses' => 'AuthController@redirect', 'as' => 'oauth.redirect']);


// add/edit, delete, etc:
Route::get('/add',['uses' => 'ContactsController@add','as' => 'contacts.add', 'before' => 'auth.google']);
Route::get('/edit/{code}',['uses' => 'ContactsController@edit','as' => 'contacts.edit', 'before' => 'auth.google']);
Route::get('/delete/{code}',['uses' => 'ContactsController@delete','as' => 'contacts.delete', 'before' => 'auth.google']);
Route::get('/mng-photo/{code}',['uses' => 'ContactsController@editPhoto','as' => 'contacts.manage-photo', 'before' => 'auth.google']);
Route::post('/add',['uses' => 'ContactsController@postAdd','before' => 'csrf|auth.google']);
Route::post('/edit/{code}',['uses' => 'ContactsController@postEdit', 'before' => 'auth.google|csrf']);
Route::post('/delete/{code}',['uses' => 'ContactsController@postDelete','before' => 'csrf|auth.google']);

// JSON and AJAX code:
Route::get('/rpc/addrow/{tpl}/{index}',['uses' => 'RpcController@addRow','as' => 'edit.addRow', 'before' => 'auth.google']);

// photos and images
Route::get('/photo/{code}',['uses' => 'PhotoController@photo','as' => 'contacts.photo', 'before' => 'auth.google']);