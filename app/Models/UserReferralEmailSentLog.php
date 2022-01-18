<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserReferralEmailSentLog extends Model
{
    public $timestamps = true;

    protected $table = 'user_referral_email_sent_log';
    protected $primaryKey = 'user_referral_email_sent_log_id';

    protected $fillable = [
        'user_id',
        'type',
        'total_sent'
    ];
    protected $guarded = [];
}
