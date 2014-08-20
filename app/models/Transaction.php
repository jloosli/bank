<?php

namespace AvantiDevelopment\JrBank\Models;
use LaravelBook\Ardent\Ardent;
class Transaction extends Ardent {
    protected $table = "transactions";

    protected $fillable = array('user_id','description','amount');

    public function user() {
        return $this->belongsTo('AvantiDevelopment\JrBank\Models\User');
    }

    public function envelope_transaction() {
        return $this->hasMany('AvantiDevelopment\JrBank\Models\EnvelopeTransaction');
    }

//    public function save(Array $options = array()) {
//        parent::save($options);
//    }

}