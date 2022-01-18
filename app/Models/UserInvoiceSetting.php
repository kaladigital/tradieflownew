<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserInvoiceSetting extends Model
{
    public $timestamps = true;

    protected $table = 'user_invoice_setting';
    protected $primaryKey = 'user_invoice_setting_id';

    protected $fillable = [
        'user_id',
        'company_name',
        'email',
        'country_id',
        'zip_code',
        'city',
        'state',
        'address',
        'gst_vat',
        'company_registration_number',
        'bank_account_holder_name',
        'bank_account_holder_type',
        'bank_account_country_id',
        'bank_account_currency',
        'bank_account_number',
        'bank_account_iban',
        'bank_account_routing_swift',
        'bank_bsb_code'
    ];
    protected $guarded = [];

    public function Country()
    {
        return $this->belongsTo('App\Models\Country','country_id','country_id');
    }
}
