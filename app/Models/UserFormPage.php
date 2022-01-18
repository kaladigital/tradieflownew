<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserFormPage extends Model
{
    public $timestamps = true;

    protected $table = 'user_form_page';
    protected $primaryKey = 'user_form_page_id';

    protected $fillable = [
        'user_form_id',
        'url',
        'has_page_scanned',
        'has_crawl_scanned'
    ];
    protected $guarded = [];
}
