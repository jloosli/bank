<?php

namespace AvantiDevelopment\JrBank;
use LaravelBook\Ardent\Ardent;

class Oauth extends Ardent {
	protected $fillable = [];

    public function user() {
        return $this->belongsTo('AvantiDevelopment\JrBank\User');
    }

    /**
     * @return string
     */
    protected static function generateToken() {
        return uniqid();
    }

    public static function boot() {
        parent::boot();

        // Automatically create a base envelope when a new user is created.
        static::creating( function ( $auth ) {
            $auth->token = self::generateToken();
            return true;
        } );
    }

    /**
     * @param $oauth_provider
     * @param $credentials
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     * @return string
     */
    public static function storeCredentials( $oauth_provider, $credentials ) {
        $user = \AvantiDevelopment\JrBank\User::where( 'email', $credentials['email'] )->first();
        if ( !$user ) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException(
                sprintf( "No users with the email address of %s are set up.", $credentials['email'] )
            );
        }
        $userAuth                 = new self;
        $userAuth->oauth_provider = $oauth_provider;
        $userAuth->oauth_uid      = $credentials['id'];
        $user->oauth()->save( $userAuth );

        return $userAuth->token;
    }

}