<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TwilioAuPhoneRegion extends Model
{
    public $timestamps = true;

    protected $table = 'twilio_au_phone_region';
    protected $primaryKey = 'twilio_au_phone_region_id';

    protected $fillable = [
        'region_code',
        'has_mobile',
        'has_local',
        'has_toll_free'
    ];
    protected $guarded = [];
}
