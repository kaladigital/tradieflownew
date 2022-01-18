<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReviewInvite extends Model
{
    public $timestamps = true;

    protected $table = 'review_invite';
    protected $primaryKey = 'review_invite_id';

    protected $fillable = [
        'user_id',
        'phone_country_id',
        'type',
        'target',
        'status',
        'unique_code',
        'twilio_sms_sid'
    ];

    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','user_id');
    }
}
