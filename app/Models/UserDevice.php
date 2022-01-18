<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    public $timestamps = true;

    protected $table = 'user_device';
    protected $primaryKey = 'user_device_id';

    protected $fillable = [
        'user_id',
        'type',
        'device_id',
        'device_token',
        'twilio_expiry_date_time'
    ];
    protected $guarded = [];
}
