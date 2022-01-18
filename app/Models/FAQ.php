<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    public $timestamps = true;

    protected $table = 'faq';
    protected $primaryKey = 'faq_id';

    protected $fillable = [
        'faq_id',
        'title',
        'description',
        'order_num'
    ];

    protected $guarded = [];
}