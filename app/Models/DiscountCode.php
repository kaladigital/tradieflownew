<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    public $timestamps = true;

    protected $table = 'discount_code';
    protected $primaryKey = 'discount_code_id';

    protected $fillable = [
        'code',
        'discount_percentage',
        'type'
    ];
}
