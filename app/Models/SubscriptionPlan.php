<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    public $timestamps = true;

    protected $table = 'subscription_plan';
    protected $primaryKey = 'subscription_plan_id';

    protected $fillable = [
        'name',
        'duration_num',
        'duration_type',
        'price_usd',
        'price_aud',
        'plan_code',
        'order_num',
        'type',
        'stripe_usd_price_id',
        'stripe_aud_price_id'
    ];
    protected $guarded = [];
}
