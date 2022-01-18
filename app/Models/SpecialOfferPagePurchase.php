<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SpecialOfferPagePurchase extends Model
{
    public $timestamps = true;

    protected $table = 'special_offer_page_purchase';
    protected $primaryKey = 'special_offer_page_purchase_id';

    protected $fillable = [
        'name',
        'email',
        'stripe_customer_id',
        'signup_code',
        'plan_code',
        'price',
        'status',
        'currency',
        'gst_amount'
    ];

    protected $guarded = [];
}
