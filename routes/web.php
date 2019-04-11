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

Route::get('/books', 'BookController@index');

Route::post('/books/subscribe', 'BookController@subscribe');

Route::get('/books/comments/{book_ID}', 'CommentController@index');

Route::post('/books/comments', 'CommentController@processForm');

Route::post('/comment', 'CommentController@comment');

Route::get('/message/{message}', 'MessageController@index');

Route::post('/users/changeRole', 'UserController@changeRole');

Route::post('/books/changeBook', 'BookController@changeBook');

Route::post('/books/removeBook', 'BookController@removeBook');

Route::post('/subscriptions/changeSubscription', 'SubscriptionController@changeSubscription');

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');
