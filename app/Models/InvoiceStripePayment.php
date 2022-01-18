<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class InvoiceStripePayment extends Model
{
    public $timestamps = true;

    protected $table = 'invoice_stripe_payment';
    protected $primaryKey = 'invoice_stripe_payment_id';

    protected $fillable = [
        'user_id',
        'invoice_id',
        'amount',
        'stripe_payment_intent_id',
        'stripe_payment_response',
        'has_stripe_wise_transferred',
        'has_customer_paid',
        'currency',
        'stripe_transfer_id',
        'stripe_transfer_response',
        'wise_quote_id',
        'wise_recipient_id',
        'wise_transfer_id'
    ];

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','user_id');
    }

    public function Invoice()
    {
        return $this->belongsTo('App\Models\Invoice','invoice_id','invoice_id');
    }
}
