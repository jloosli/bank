<?php
namespace AvantiDevelopment\JrBank\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Envelope extends Eloquent {

    // For some reason, Ardent doesn't work with the softDelete Trait
    // The following two lines should be uncommented when upgraded to 4.2 and Ardent is fixed
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

    // This should be deleted when ardent starts to work for this.
    protected $softDelete = true;

    protected $fillable = array( 'user_id', 'name', 'goal', 'goal_date', 'percent', 'default_spend' );

    public static $rules = [
        'name'        => 'required'
    ];

    public function user() {
        return $this->belongsTo( 'AvantiDevelopment\JrBank\Models\User' );
    }

    public function envelope_transactions() {
        return $this->hasMany( 'AvantiDevelopment\JrBank\Models\EnvelopeTransaction' );
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

    public function getUserIdAttribute( $value ) {
        return (int) $value;
    }

    public function getDefaultSpendAttribute($value) {
        return $value === '1';
    }
}