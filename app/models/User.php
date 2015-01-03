<?php

namespace AvantiDevelopment\JrBank\Models;

use Eloquent;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Watson\Validating\ValidatingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface {

    use SoftDeletingTrait;
    use ValidatingTrait;
    protected $dates = ['deleted_at'];

    protected $fillable = array( 'username', 'name', 'email', 'bank_id', 'password', 'user_type' );
    protected $guarded = [ 'id' ];

    public function transactions() {
        return $this->hasMany( 'AvantiDevelopment\JrBank\Models\Transaction' );
    }

    public function bank() {
        return $this->belongsTo( 'AvantiDevelopment\JrBank\Models\Bank' );
    }

    public function envelopes() {
        return $this->hasMany( 'AvantiDevelopment\JrBank\Models\Envelope' );
    }

    public function sessions() {
        return $this->hasMany( 'Token' );
    }


    public function getRememberToken() {
        return $this->rememberToken;
    }

    public function getRememberTokenName() {
        return 'remember_token';
    }

    public function setRememberToken( $token ) {
        $this->remember_token = $token;
    }

    /**
     * Validation rules
     */
    protected $rules = array(
        'username' => 'required|between:4,100',
        'email'    => 'required|email',
        'password' => 'min:3',
        'bank_id'  => 'required|numeric',
        'slug'     => 'required|unique:users,slug',
        'user_type' => 'required'
    );

    protected $observables = ['creating'];

    public static function boot() {
        parent::boot();

        // Automatically create a base envelope when a new user is created.
        static::created( function ( $user ) {
            $baseEnvelope                = new Envelope();
            $baseEnvelope->user_id       = $user->id;
            $baseEnvelope->name          = "Spending";
            $baseEnvelope->default_spend = 1;
            $baseEnvelope->percent       = 100;
            $baseEnvelope->balance       = 0;
            $baseEnvelope->save();

            return true;
        } );

        static::creating(function($user) {
            // Start off with 0 balance;
            $user->balance = 0;


        });

    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'password',
        'deleted_at',
        'token',
        'foursquare',
        'github',
        'google',
        'linkedin',
        'twitter',
        'facebook'
    );

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    public static function getCreateRules() {
        return self::$createRules;
    }

    public static function getAuthFBRules() {
        return self::$fb_authRules;
    }

    public static function getAuthRules() {
        return self::$authRules;
    }

    public function isOwnerOf( $token ) {
        $owner = Token::userFor( $token );
        if ( empty( $owner ) || $owner->user_id != $this->id ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Generate a token to authenticate a user
     *
     * @return mixed
     */
    public function login( $device_id = null, $device_type = null, $device_token = null ) {

        // clear old sessions for any user with: same(device_id, os)
        $to_remove = Token::where( 'device_id', '=', $device_id )
                          ->where( 'device_os', '=', $device_type )
                          ->delete();

        $token               = Token::getInstance();
        $token->user_id      = $this->id;
        $token->device_id    = $device_id;
        $token->device_os    = $device_type;
        $token->device_token = $device_token;
        $token->save();

        return $token;
    }


    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
        return $this->email;
    }

    /* Set accessors so I'm always getting actual values */
    public function getBankIdAttribute( $value ) {
        return (int) $value;
    }

    public function getBalanceAttribute( $value ) {
        return (float) $value;
    }

    public function getIdAttribute( $value ) {
        return (int) $value;
    }

}