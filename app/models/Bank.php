<?php

use LaravelBook\Ardent\Ardent;

class Bank extends Ardent {

    protected $hidden = [ 'password' ];
    protected $guarded = [ 'id', 'deleted_at' ];
    protected $fillable = [ 'name', 'password', 'interest', 'compounding' ];
    protected $softDelete = true;

    public static $rules = [
        'name'        => 'required|unique:banks,name',
        'password'    => 'required',
        'compounding' => 'required'
    ];

    public function users() {
        return $this->hasMany( 'User' );
    }

    /* Set accessors so I'm always getting actual values */
    public function getInterestAttribute( $value ) {
        return (float) $value;
    }

    public function getIdAttribute( $value ) {
        return (int) $value;
    }
}