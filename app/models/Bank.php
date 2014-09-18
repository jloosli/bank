<?php


namespace AvantiDevelopment\JrBank\Models;
use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Watson\Validating\ValidatingTrait;

class Bank extends Eloquent {
    // For some reason, Ardent doesn't work with the softDelete Trait
    // The following two lines should be uncommented when upgraded to 4.2 and Ardent is fixed
    use SoftDeletingTrait;
    use ValidatingTrait;
    protected $dates = ['deleted_at'];

    // This should be deleted when ardent starts to work for this.
    protected $softDelete = true;


    protected $hidden = [ 'password' ];
    protected $guarded = [ 'id', 'deleted_at' ];
    protected $fillable = [ 'name', 'password', 'interest', 'compounding' ];
//    protected $softDelete = true;

    protected $rules = [
        'name'        => 'required|unique:banks,name',
        'password'    => 'required',
        'compounding' => 'required'
    ];

    public function users() {
        return $this->hasMany( 'AvantiDevelopment\JrBank\Models\User' );
    }

    /* Set accessors so I'm always getting actual values */
    public function getInterestAttribute( $value ) {
        return (float) $value;
    }

    public function getIdAttribute( $value ) {
        return (int) $value;
    }
}