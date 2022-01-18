<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    public $timestamps = true;

    protected $table = 'business_type';
    protected $primaryKey = 'business_type_id';

    protected $fillable = [
        'name',
        'order_num',
        'active'
    ];

    protected $guarded = [];
}
