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


}