<?php

namespace AvantiDevelopment\JrBank;
class OauthUserProviderInterface implements \Illuminate\Auth\UserProviderInterface {

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     *
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveById( $identifier ) {
        return User::find($identifier);
    }

    /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string $token
     *
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByToken( $identifier, $token ) {
        $auth = \AvantiDevelopment\JrBank\Oauth::where('token',$token);
        if(!$auth) {
            return;
        }
        $user = $auth->user();
        return $user;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Auth\UserInterface $user
     * @param  string                         $token
     *
     * @return void
     */
    public function updateRememberToken( \Illuminate\Auth\UserInterface $user, $token ) {
        // TODO: Implement updateRememberToken() method.
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     *
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByCredentials( array $credentials ) {
        $auth =  \AvantiDevelopment\JrBank\Oauth::where('token', $credentials['email'])->first();
        if (!$auth) {
            return;
        }
        $user = $auth->user();
        return $user;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Auth\UserInterface $user
     * @param  array                          $credentials
     *
     * @return bool
     */
    public function validateCredentials( \Illuminate\Auth\UserInterface $user, array $credentials ) {
        return $this->retrieveByCredentials($credentials) === $user;
}}