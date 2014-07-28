<?php

namespace AvantiDevelopment;

class Oauth extends \Eloquent {
	protected $fillable = [];

    public function user() {
        $this->belongsTo('User');
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


}