<?php
use LaravelBook\Ardent\Ardent;

class Envelope extends Ardent {

    protected $softDelete = true;

    protected $fillable = array( 'user_id', 'name', 'goal', 'goal_date', 'percent', 'default_spend' );

    public static $rules = [
        'name'        => 'required'
    ];

    public function user() {
        return $this->belongsTo( 'User' );
    }

    public function envelope_transactions() {
        return $this->hasMany( 'EnvelopeTransaction' );
    }

    public function getGoalAttribute( $value ) {
        return (float) $value;
    }

    public function getPercentAttribute( $value ) {
        return (int) $value;
    }

    public function getBalanceAttribute( $value ) {
        return (float) $value;
    }

    public function getIdAttribute( $value ) {
        return (int) $value;
    }
}