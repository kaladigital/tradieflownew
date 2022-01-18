<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    public $timestamps = true;

    protected $table = 'quote';
    protected $primaryKey = 'quote_id';

    protected $fillable = [
        'user_id',
        'client_name',
        'client_contact_person',
        'client_phone_number',
        'client_email',
        'client_address',
        'client_city',
        'client_state',
        'client_zip',
        'client_country_id',
        'company_name',
        'company_contact_person',
        'company_phone_number',
        'company_email',
        'company_address',
        'company_city',
        'company_state',
        'company_zip',
        'company_country_id',
        'expiry_date',
        'special_discount_percentage',
        'special_discount_within_days',
        'special_discount_days',
        'message',
        'company_logo'
    ];

    protected $guarded = [];
}
