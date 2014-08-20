<?php

namespace AvantiDevelopment\JrBank\Models;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use LaravelBook\Ardent\Ardent;

class User extends Ardent implements UserInterface, RemindableInterface {

    protected $softDelete = true;

    protected $fillable = array( 'username', 'name', 'email', 'bank_id', 'password' );
    protected $guarded = [ 'id', 'deleted_at' ];

    public function transactions() {
        return $this->hasMany( 'AvantiDevelopment\JrBank\Models\Transaction' );
    }

    public function bank() {
        return $this->belongsTo( 'AvantiDevelopment\JrBank\Models\Bank' );
    }

    public function oauth() {
        return $this->hasMany( 'AvantiDevelopment\JrBank\Models\Oauth' );
    }

    public function envelopes() {
        return $this->hasMany( 'AvantiDevelopment\JrBank\Models\Envelope' );
    }

    public function sessions() {
        return $this->hasMany('Token');
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
     * Ardent validation rules
     */
    public static $rules = array(
        'username' => 'required|between:4,16',
        'email'    => 'required|email',
        'password' => 'min:3',
        'bank_id'  => 'required|numeric',
        'slug'     => 'unique:users,slug,bank_id'
    );

    protected static $createRules = array(
        'firstname'             => 'required',
        'lastname'              => 'required',
        'password'              => 'required|min:6|confirmed',
        'password_confirmation' => 'required|min:6',
        'email'                 => 'required|email|unique:users,email',
    );
    protected static $authRules = array(
        'email'    => 'required|email',
        'password' => 'required',
        // 'device_id'				=>	'required',
        // 'device_type'			=>	'required',
        // 'device_token'			=>	'required',
    );
    protected static $fb_authRules = array(
        'access_token' => 'required',
        // 'device_id'				=>	'required',
        // 'device_type'			=>	'required',
        // 'device_token'			=>	'required',
    );


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
    protected $hidden = array( 'password', 'deleted_at', 'token' );

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

    public function isOwnerOf($token) {
        $owner = Token::userFor( $token );
        if ( empty($owner) || $owner->user_id!=$this->id )
            return false;
        else
            return true;
    }

    /**
     * Generate a token to authenticate a user
     *
     * @return mixed
     */
    public function login( $device_id=null, $device_type=null, $device_token=null ) {

        // clear old sessions for any user with: same(device_id, os)
        $to_remove = Token::where('device_id', '=', $device_id)
                          ->where('device_os', '=', $device_type)
                          ->delete();

        $token = Token::getInstance();
        $token->user_id	= $this->id;
        $token->device_id = $device_id;
        $token->device_os =	$device_type;
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