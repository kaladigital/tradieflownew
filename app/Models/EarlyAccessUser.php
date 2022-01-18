<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EarlyAccessUser extends Model
{
    public $timestamps = true;

    protected $table = 'early_access_user';
    protected $primaryKey = 'early_access_user_id';

    protected $fillable = [
        'email',
        'amount',
        'payment_details',
        'subscription_plan_id',
        'subscription_plan_code',
        'type',
        'has_discount_accepted',
        'currency',
        'gst_amount'
    ];
}
