<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserSubscriptionLog extends Model
{
    public $timestamps = true;

    protected $table = 'user_subscription_log';
    protected $primaryKey = 'user_subscription_log_id';

    protected $fillable = [
        'user_subscription_id',
        'user_id',
        'subscription_plan_id',
        'subscription_plan_name',
        'subscription_plan_code',
        'active',
        'payment_response',
        'expiry_date_time',
        'final_expiry_date_time',
        'is_extendable',
        'price',
        'last_popup_notification_date',
        'type',
        'discount_code',
        'discount_code_id',
        'discounted_price',
        'discount_pay_expiry_date',
        'currency',
        'gst_amount'
    ];
    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','user_id');
    }
}
