<?php

class Bank extends Eloquent {
//    protected $table = 'banks';

    public function users() {
        return $this->hasMany('User');
    }
}