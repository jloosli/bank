<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use LaravelBook\Ardent\Ardent;

class User extends Eloquent { //Ardent implements UserInterface, RemindableInterface {

    protected $fillable=array('username','name','email','bank_id', 'password');

    public function transactions() {
        return $this->hasMany('Transaction');
    }

    public function bank() {
        return $this->belongsTo('Bank');
    }

    public function envelopes() {
        return $this->hasMany('Envelope');
    }

    public function getRememberToken() {

    }
    public function getRememberTokenName() {

    }
    public function setRememberToken($token) {

    }

    /**
     * Ardent validation rules
     */
    public static $rules = array(
        'username' => 'required|between:4,16',
        'email' => 'required|email',
        'password' => 'min:3',
        'bank_id'=>'required|numeric',

    );

    public static function boot() {
        parent::boot();

        // Automatically create a base envelope when a new user is created.
        static::created(function($user) {
            $baseEnvelope = new Envelope();
            $baseEnvelope->user_id = $user->id;
            $baseEnvelope->name = "Spending";
            $baseEnvelope->default_spend = 1;
            $baseEnvelope->percent = 100;
            $baseEnvelope->save();
            return true;
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
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
}