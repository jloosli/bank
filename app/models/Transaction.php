<?php

namespace AvantiDevelopment\JrBank;

class Transaction extends Eloquent {
    protected $table = "transactions";

    protected $fillable = array('user_id','description','amount');

    public function user() {
        return $this->belongsTo('User');
    }

    public function envelope_transaction() {
        return $this->hasMany('EnvelopeTransaction');
    }

    public function save(Array $options = array()) {
        parent::save($options);
    }

}