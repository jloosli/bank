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

Route::group(array('prefix'=>'/api/v1', 'before' => 'auth.token', ), function() {
    Route::post('users/{id}','UserController@update');
    Route::resource('users','UserController');
    Route::resource('banks','BankController');
    Route::resource('transactions','TransactionController');
    Route::resource('envelopes','EnvelopeController');
    Route::get('transactions/user/{id}', 'TransactionController@user');
    Route::delete('auth', 'Tappleby\AuthToken\AuthTokenController@destroy');
    Route::get('auth', 'Tappleby\AuthToken\AuthTokenController@index');
});

Route::post('/api/v1/auth', 'Tappleby\AuthToken\AuthTokenController@store');


