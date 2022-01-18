<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserFormPageForm extends Model
{
    public $timestamps = true;

    protected $table = 'user_form_page_form';
    protected $primaryKey = 'user_form_page_form_id';

    protected $fillable = [
        'user_form_page_id',
        'form_name',
        'form_type',
        'allow_track',
        'display_name'
    ];
    protected $guarded = [];
}
