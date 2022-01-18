<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    public $timestamps = true;

    protected $table = 'invoice_item';
    protected $primaryKey = 'invoice_item_id';

    protected $fillable = [
        'invoice_id',
        'title',
        'description',
        'unit_price',
        'tax_rate',
        'qty',
        'order_num'
    ];
}
