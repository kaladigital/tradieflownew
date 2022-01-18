<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserGoogleToken extends Model
{
    public $timestamps = true;

    protected $table = 'user_google_token';
    protected $primaryKey = 'user_google_token_id';

    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token'
    ];
    protected $guarded = [];
}
