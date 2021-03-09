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
->middleware('verified');
 */

Route::get('/', 'DashboardController@home')->name('home');

Auth::routes(['verify' => true]);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('/users/{user}', 'UsersController@show')->name('profile')->middleware('verified');
    Route::get('/users/{user}/edit', 'UsersController@edit')->name('editProfile')->middleware('verified');
    Route::post('/users/{user}', 'UsersController@update')->name('updateProfile')->middleware('verified');

    Route::get('orders/create', 'OrdersController@create')->name('createOrder')->middleware('verified');
    Route::post('orders', 'OrdersController@store')->name('storeOrder')->middleware('verified'); /** --------------- */
    // Route::get('orders/{order}', 'OrdersController@confirm')->name('confirm')->middleware('verified');
    // Route::get('orders/', 'OrdersController@index')->name('ordersList')->middleware('verified'); /** ---------------- */
    Route::get('orders', 'OrdersController@getOrders')->name('yajraList')->middleware('verified'); /** --------------- */
});

Route::middleware('admin')->prefix('admin')->group(function(){
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('users', 'UsersController@getUsers')->name('usersList')->middleware('verified');

    Route::get('/users/{user}', 'UsersController@details')->name('details')->middleware('verified');
    Route::get( 'getUserOrders/{user}', 'UsersController@getUserOrders')->name('userOrders')->middleware('verified');

    Route::get('orders', 'OrdersController@getAllOrders')->name('getAllOrders')->middleware('verified');
    Route::get('orders/{order}/confirm', 'OrdersController@confirm')->name('confirm')->middleware('verified');
    Route::delete('orders/{order}', 'OrdersController@destroy')->name('deleteOrder')->middleware('verified');
    Route::delete('deleteorders/{orders}', 'OrdersController@multipleDelete')->name('multipleDelete')->middleware('verified');
    Route::get('orders/{order}/edit', 'OrdersController@edit')->name('editOrder')->middleware('verified');
    Route::post('orders/{order}', 'OrdersController@update')->name('updateOrder')->middleware('verified');
    Route::get('orders/{order}/update', 'OrdersController@editFromOrders')->name('editFromOrders')->middleware('verified');
    Route::post('orders/{order}/update', 'OrdersController@updateFromOrders')->name('updateFromOrders')->middleware('verified');

    Route::get('pdf', 'OrdersController@pdf')->name('convertPdf')->middleware('verified');
    Route::get('excel', 'OrdersController@excel')->name('convertExcel')->middleware('verified');

    Route::get('/users/{user}/edit', 'UsersController@editUser')->name('editUserProfile')->middleware('verified');
    Route::post('/users/{user}', 'UsersController@updateUser')->name('updateUserProfile')->middleware('verified');

});
