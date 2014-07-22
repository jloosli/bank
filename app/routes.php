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

Route::group(['prefix'=>'/api'], function () {
    Route::group(['prefix'=>'/banks'], function () {
        Route::get('/', function () {
            // Get all banks (super admin only)
            return Bank::all();
        });
        Route::post('/',function() {
            // Create new bank
        });
        Route::get('/{id}', function ($id) {
            // Get information for one bank
            return Bank::findOrFail($id);
        });
        Route::put('/{id}', function ($id) {
            // Update bank information
        });
        Route::delete('/{id}', function ($id) {
            // Delete (archive) bank
        });

        /* Users */
        Route::get('/{id}/users', function($id) {
            // Get all users for the bank
        });
        Route::get('/{id}/users/{user_id}', function($id, $user_id) {
            // Get details about a user
        });
        Route::put('/{id}/users/{user_id}', function($id, $user_id) {
            // Update user
        });
        Route::post('/{id}/users', function($id) {
            // Add new user
        });
        Route::delete('/{id}/users/{user_id}', function ($id, $user_id) {
            // Delete user
        });

        /* Envelopes */
        Route::get('/{id}/users/{user_id}/envelopes', function($id, $user_id) {
            // Get user's envelopes
        });
        Route::get('/{id}/users/{user_id}/envelopes/{env_id}', function($id, $user_id, $env_id) {
            // Get details about specific envelope
        });
        Route::put('/{id}/users/{user_id}/envelopes/{env_id}', function($id, $user_id, $env_id) {
            // Update user envelope
        });
        Route::delete('/{id}/users/{user_id}/envelopes/{env_id}', function($id, $user_id, $env_id) {
            // Delete user envelope
        });
        Route::post('/{id}/users/{user_id}/envelopes/', function($id, $user_id) {
            // Add new envelope
        });

        /* Transactions */
        Route::get('/{id}/users/{user_id}/transactions', function($id, $user_id) {
            // Get all transactions (pagination get queries expected)
        });
        Route::post('/{id}/users/{user_id}/transactions', function($id,$user_id) {
            // Create new transaction
        });
        Route::get('/{id}/users/{user_id}/transactions/{trans_id}', function($id,$user_id,$trans_id) {
            // Get specific transaction
        });

    });

});


