<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserTwilioPhoneRedirect extends Model {

    public $timestamps = true;

    protected $table = 'user_twilio_phone_redirect';
    protected $primaryKey = 'user_twilio_phone_redirect_id';

    protected $fillable = [
        'user_twilio_phone_id',
        'country_id',
        'name',
        'phone',
        'phone_format'
    ];

    protected $guarded = [];

    public function Country()
    {
        return $this->belongsTo('App\Models\Country','country_id','country_id');
    }
}
