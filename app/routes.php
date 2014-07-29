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
    Route::group( [ 'prefix' => '/banks',  'protected' => true ], function () {
        Route::get( '/', 'BankController@index' ); // Get all banks (super admin only)
        Route::post( '/', 'BankController@store' ); // Create new bank
        Route::get( '/{id}', 'BankController@show' ); // Get details on single bank
        Route::put( '/{id}', 'BankController@update' ); // Update bank information
        Route::delete( '/{id}', 'BankController@destroy' ); // Delete (archive) bank

        /* Users */
        Route::get( '/{id}/users', 'UserController@index' ); // Get all users for the bank
        Route::get( '/{id}/users/{user_id}', 'UserController@show' ); // Get details about a user
        Route::put( '/{id}/users/{user_id}', 'UserController@update' ); // Update user
        Route::post( '/{id}/users', 'UserController@store' ); // Add new user
        Route::delete( '/{id}/users/{user_id}', 'UserController@destroy' ); // Delete user

        /* Envelopes */
        Route::get( '/{id}/users/{user_id}/envelopes', 'EnvelopeController@index' ); // Get user's envelopes
        Route::get( '/{id}/users/{user_id}/envelopes/{env_id}', 'EnvelopeController@show' ); // Get details about specific envelope
        Route::put( '/{id}/users/{user_id}/envelopes/{env_id}', 'EnvelopeController@update' ); // Update user envelope
        Route::delete( '/{id}/users/{user_id}/envelopes/{env_id}', 'EnvelopeController@destroy' ); // Delete user envelope
        Route::post( '/{id}/users/{user_id}/envelopes/', 'EnvelopeController@store' ); // Add new envelope

        /* Transactions */
        Route::get( '/{id}/users/{user_id}/transactions', 'TransactionController@index' ); // Get all transactions (pagination get queries expected)
        Route::post( '/{id}/users/{user_id}/transactions', 'TransactionController@store' ); // Create new transaction
        Route::get( '/{id}/users/{user_id}/transactions/{trans_id}', 'TransactionController@show' ); // Get specific transaction

    } );

} );

Route::any('oauth/google','AvantiDevelopment\JrBank\OauthController@loginWithGoogle');