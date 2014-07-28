<?php

namespace AvantiDevelopment\JrBank;



use LaravelBook\Ardent\Ardent;

class EnvelopeTransaction extends Ardent {
    protected $table = 'envelope_transactions';

	protected $guarded = array();

    protected $fillable = ['amount','envelope_id'];

	public static $rules = array();

    public function envelope() {
        return $this->hasMany('Envelope');
    }

    public function transaction() {
        return $this->hasMany('Transaction');
    }

}
