<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/',['uses' => 'HomeController@index', 'as' => 'index','before' => 'auth.google.reversed']);
Route::get('/home',['uses' => 'HomeController@home', 'as' => 'home','before' => 'auth.google']);

//Route::post('/login',['uses' => 'AuthController@login', 'as' => 'oauth.form-submit','before' => 'csrf']);
Route::post('/login',['uses' => 'AuthController@loginOAuth2', 'as' => 'oauth.form-submit','before' => 'csrf']);

Route::get('/oauth2callback',['uses' => 'AuthController@oauth2callback','as' => 'oauth2callback']);
Route::get('/logout',['uses' => 'AuthController@logout', 'as' => 'oauth.logout','before' => 'auth.google']);
Route::get('/auth',['uses' => 'AuthController@redirect', 'as' => 'oauth.redirect']);

// add/edit, delete, etc:
Route::get('/add',['uses' => 'ContactsController@add','as' => 'contacts.add', 'before' => 'auth.google']);
Route::post('/add',['uses' => 'ContactsController@postAdd','before' => 'csrf|auth.google']);

Route::get('/edit/{code}',['uses' => 'ContactsController@edit','as' => 'contacts.edit', 'before' => 'auth.google']);
Route::post('/edit/{code}',['uses' => 'ContactsController@postEdit', 'before' => 'auth.google|csrf']);

Route::get('/delete/{code}',['uses' => 'ContactsController@delete','as' => 'contacts.delete', 'before' => 'auth.google']);
Route::post('/delete/{code}',['uses' => 'ContactsController@postDelete','before' => 'csrf|auth.google']);

// JSON and AJAX code:
Route::get('/rpc/contacts/all',['uses' => 'RpcController@allContacts','as' => 'contacts', 'before' => 'auth.google']);
Route::get('/rpc/addrow/{tpl}/{index}',['uses' => 'RpcController@addRow','as' => 'edit.addRow', 'before' => 'auth.google']);

// photos and images
Route::get('/photo/{code}',['uses' => 'PhotoController@photo','as' => 'contacts.photo', 'before' => 'auth.google']);

// dev stuff:
Route::get('/dev',['uses' => 'DevController@test']);
Route::get('/setup',['uses' => 'DevController@setup']);

Route::get('/withAuth',['uses' => 'DevController@withAuth','as' => 'withAuth'] );

