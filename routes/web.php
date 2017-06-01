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

Route::any('try-and-buy/regenerate', 'TryAndBuyController@regenerate')->name('try_and_buy.regenerate');
Route::post('try-and-buy/validate', 'TryAndBuyController@nlicValidate')->name('try_and_buy.validate');
Route::get('try-and-buy/shop-success', 'TryAndBuyController@shopSuccess')->name('try_and_buy.shop_success');
Route::get('try-and-buy/shop-cancel', 'TryAndBuyController@shopCancel')->name('try_and_buy.shop_cancel');


Route::get('subscription', 'SubscriptionController@index')->name('subscription');

Route::any('subscription/regenerate', 'SubscriptionController@regenerate')->name('subscription.regenerate');
Route::post('subscription/validate', 'SubscriptionController@nlicValidate')->name('subscription.validate');
Route::get('subscription/shop-success', 'SubscriptionController@shopSuccess')->name('subscription.shop_success');
Route::get('subscription/shop-cancel', 'SubscriptionController@shopCancel')->name('subscription.shop_cancel');