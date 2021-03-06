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

Route::pattern('post', '[0-9]+');

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/post/{post}', 'PostController@show')->name('post.show');

Route::get('/profile/{user}', 'ProfileController@show')->name('profile.show');

Route::group(['middleware' => 'auth'], function() {
    Route::post('/profile/{user}/comment', 'ProfileController@comment')->name('profile.comment');

    Route::get('/post/create', 'PostController@create')->name('post.create');
    Route::post('/post/create', 'PostController@store');

    Route::get('/post/{post}/edit', 'PostController@edit')->name('post.edit');
    Route::post('/post/{post}/edit', 'PostController@update');

    Route::post('/post/{post}/upload-image', 'PostController@uploadImage')->name('post.upload-image');
    Route::post('/post/{post}/delete-image', 'PostController@deleteImage')->name('post.delete-image');

    Route::post('/post/{post}/comment', 'PostController@comment')->name('post.comment');

    Route::post('/comment/{comment}/reply', 'CommentController@reply')->name('comment.reply');
});
