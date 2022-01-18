<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserXeroAccount extends Model {

    public $timestamps = true;

    protected $table = 'user_xero_token';
    protected $primaryKey = 'user_xero_token_id';

    protected $fillable = [
        'user_id',
        'access_token',
        'tenant_id',
        'refresh_token',
        'active'
    ];

    protected $guarded = [];
}
