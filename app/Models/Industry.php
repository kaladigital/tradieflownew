<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    public $timestamps = true;

    protected $table = 'industry';
    protected $primaryKey = 'industry_id';

    protected $fillable = [
        'name',
        'order_num',
        'active'
    ];

    protected $guarded = [];
}
