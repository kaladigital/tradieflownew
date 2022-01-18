<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserXeroMobileRedirect extends Model {

    public $timestamps = true;

    protected $table = 'user_xero_mobile_redirect';
    protected $primaryKey = 'user_xero_mobile_redirect_id';

    protected $fillable = [
        'user_id',
        'code'
    ];

    protected $guarded = [];
}
