<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserReferralMonthQueue extends Model
{
    public $timestamps = true;

    protected $table = 'user_referral_month_queue';
    protected $primaryKey = 'user_referral_month_queue_id';

    protected $fillable = [
        'sent_user_id',
        'received_user_id',
        'has_admin_sent',
        'status'
    ];
    protected $guarded = [];
}


