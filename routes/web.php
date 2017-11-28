<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// OAuth Routes
Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');

//exit($_SERVER['REQUEST_URI']);
// Tasks
Route::get('/tasks','TasksController@index')->name('tasks.index');
Route::get('/tasks/create', 'TasksController@create')->name('tasks.create');
Route::post('/tasks', 'TasksController@store')->name('tasks.store');
Route::get('/tasks/{task}/edit', 'TasksController@edit')->name('tasks.edit');
Route::post('/tasks/{task}/update', 'TasksController@update')->name('tasks.update');
Route::get('/tasks/{task}', 'TasksController@destroy')->name('tasks.delete');

// Cat category editor
Route::get('/cat','CatController@index')->name('cat.index');
Route::get('/cat/create', 'CatController@create')->name('cat.create');
Route::post('/cat', 'CatController@store')->name('cat.store');
Route::get('/cat/{cat}/edit', 'CatController@edit')->name('cat.edit');
Route::post('/cat/{cat}/update', 'CatController@update')->name('cat.update');
Route::get('/cat/{cat}', 'CatController@destroy')->name('cat.delete');