<?php

namespace AvantiDevelopment\JrBank\Models;




use Eloquent;

class EnvelopeTransaction extends Eloquent {
    protected $table = 'envelope_transactions';

	protected $guarded = array();

    protected $fillable = ['amount','envelope_id'];

	public static $rules = array();

    public function envelope() {
        return $this->hasMany('AvantiDevelopment\JrBank\Models\Envelope');
    }

    public function transaction() {
        return $this->hasMany('AvantiDevelopment\JrBank\Models\Transaction');
    }

}
