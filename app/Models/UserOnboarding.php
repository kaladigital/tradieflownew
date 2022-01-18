<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserOnboarding extends Model
{
    public $timestamps = true;

    protected $table = 'user_onboarding';
    protected $primaryKey = 'user_onboarding_id';

    protected $fillable = [
        'user_id',
        'account',
        'phone_numbers',
        'calendar',
        'forms',
        'invoices',
        'integrations',
        'subscriptions',
        'help',
        'type'
    ];
    protected $guarded = [];
}
