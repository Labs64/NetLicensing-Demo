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

Route::get('/', 'HomeController@index')->name('home');
Route::get('try-and-buy', 'TryAndBuyController@index')->name('try_and_buy');
Route::get('subscription', 'SubscriptionController@index')->name('subscription');

