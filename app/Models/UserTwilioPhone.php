<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserTwilioPhone extends Model {

    public $timestamps = true;

    protected $table = 'user_twilio_phone';
    protected $primaryKey = 'user_twilio_phone_id';

    protected $fillable = [
        'user_id',
        'friendly_name',
        'phone',
        'country_code',
        'twilio_address_sid',
        'twilio_bundle_sid',
        'twilio_sid',
        'status'
    ];

    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','user_id');
    }

    public function UserTwilioPhoneRedirect()
    {
        return $this->hasMany('App\Models\UserTwilioPhoneRedirect','user_twilio_phone_id','user_twilio_phone_id');
    }
}
