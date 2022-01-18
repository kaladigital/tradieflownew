<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {

    public $timestamps = true;

    protected $table = 'invoice';
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'user_id',
        'client_id',
        'country_id',
        'phone',
        'email',
        'city',
        'state',
        'zip',
        'address',
        'gst_number',
        'issued_date',
        'due_date',
        'fulfillment_date',
        'payment_deadline_days',
        'payment_method',
        'currency',
        'discount_type',
        'discount',
        'online_payment_type',
        'is_recurring',
        'status',
        'note',
        'is_public_note',
        'net_value_without_tax',
        'tax_amount',
        'amount_without_discount',
        'discount_amount',
        'total_gross_amount',
        'has_paid',
        'xero_invoice_id',
        'invoice_unique_number',
        'invoice_number_label',
        'paid_date',
        'recurring_type',
        'recurring_num',
        'next_recurring_date'
    ];

    protected $guarded = [];

    public function Client()
    {
        return $this->belongsTo('App\Models\Client','client_id','client_id');
    }

    public function Country()
    {
        return $this->belongsTo('App\Models\Country','country_id','country_id');
    }

    public function InvoiceItem()
    {
        return $this->hasMany('App\Models\InvoiceItem','invoice_id','invoice_id');
    }

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','user_id');
    }
}
