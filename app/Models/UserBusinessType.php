<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserBusinessType extends Model
{
    public $timestamps = true;

    protected $table = 'user_business_type';
    protected $primaryKey = 'user_business_type_id';

    protected $fillable = [
        'user_id',
        'business_type_id'
    ];

    protected $guarded = [];
}
