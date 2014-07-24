<?php

use LaravelBook\Ardent\Ardent;

class Bank extends Ardent {
    public static $name = 'bank';

    public $hidden = ['password'];

    public static $rules = [
        'name' => 'required|unique:banks,id',
        'password' => 'required',
        'compounding' => 'required'
    ];

    public function users() {
        return $this->hasMany('User');
    }

    /* Set accessors so I'm always getting actual values */
    public function getInterestAttribute ($value) {
        return (float) $value;
    }
    public function getIdAttribute ($value) {
        return (int) $value;
    }
}