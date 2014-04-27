<?php

class EnvelopeTransaction extends Eloquent {
    protected $table = 'envelope_transactions';

	protected $guarded = array();

	public static $rules = array();

    public function envelope() {
        return $this->hasMany('Envelope');
    }

    public function transaction() {
        return $this->hasMany('Transaction');
    }

}
