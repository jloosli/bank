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

Route::api( [ 'version' => 'v1' ], function () {
    Route::get('/test', function() {return "Test";});
    Route::get( '/users/me', 'UserController@currentUser' ); // Get the current user
    Route::put( '/users/me', 'UserController@updateUser' ); // Get the current user
    Route::group( [ 'prefix' => '/banks', 'protected' => true ], function () {
        Route::get( '/', 'BankController@index' ); // Get all banks (super admin only)
        Route::post( '/', 'BankController@store' ); // Create new bank
        Route::get( '/{bank_id}', 'BankController@show' ); // Get details on single bank
        Route::put( '/{bank_id}', 'BankController@update' ); // Update bank information
        Route::delete( '/{bank_id}', 'BankController@destroy' ); // Delete (archive) bank

        /* Users */

        Route::get( '/{bank_id}/users', 'UserController@index' ); // Get all users for the bank
        Route::get( '/{bank_id}/users/{user_id}', 'UserController@show' ); // Get details about a user
        Route::put( '/{bank_id}/users/{user_id}', 'UserController@update' ); // Update user
        Route::post( '/{bank_id}/users', 'UserController@store' ); // Add new user
        Route::delete( '/{bank_id}/users/{user_id}', 'UserController@destroy' ); // Delete user

        /* Envelopes */
        Route::get( '/{bank_id}/users/{user_id}/envelopes', 'EnvelopeController@index' ); // Get user's envelopes
        Route::get( '/{bank_id}/users/{user_id}/envelopes/{env_id}', 'EnvelopeController@show' ); // Get details about specific envelope
        Route::put( '/{bank_id}/users/{user_id}/envelopes/{env_id}', 'EnvelopeController@update' ); // Update user envelope
        Route::delete( '/{bank_id}/users/{user_id}/envelopes/{env_id}', 'EnvelopeController@destroy' ); // Delete user envelope
        Route::post( '/{bank_id}/users/{user_id}/envelopes/', 'EnvelopeController@store' ); // Add new envelope
        http://jrbank.co/api/1/users/1/envelopes
        /* Transactions */
        Route::get( '/{bank_id}/users/{user_id}/transactions', 'TransactionController@index' ); // Get all transactions (pagination get queries expected)
        Route::post( '/{bank_id}/users/{user_id}/transactions', 'TransactionController@store' ); // Create new transaction
        Route::get( '/{bank_id}/users/{user_id}/transactions/{trans_id}', 'TransactionController@show' ); // Get specific transaction

    } );

} );
Route::get('/test', function() {return "Test";});
Route::post('auth/login', 'AuthController@login');
Route::post('auth/signup', 'AuthController@signup');
Route::post('auth/facebook', 'AuthController@facebook');
Route::post('auth/foursquare', 'AuthController@foursquare');
Route::post('auth/github', 'AuthController@github');
Route::post('auth/google', 'AuthController@google');
Route::post('auth/linkedin', 'AuthController@linkedin');
Route::get('auth/twitter', 'AuthController@twitter');
Route::get('auth/unlink/{provider}', array('before' => 'auth', 'uses' => 'AuthController@unlink'));
