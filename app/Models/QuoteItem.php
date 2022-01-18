<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    public $timestamps = true;

    protected $table = 'quote_item';
    protected $primaryKey = 'quote_item_id';

    protected $fillable = [
        'quote_id',
        'title',
        'description',
        'price',
        'tax_rate_percentage',
        'qty',
        'order_num'
    ];

    protected $guarded = [];
}
