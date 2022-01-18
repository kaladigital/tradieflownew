<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserFormData extends Model
{
    public $timestamps = true;

    protected $table = 'user_form_data';
    protected $primaryKey = 'user_form_data_id';

    protected $fillable = [
        'user_id',
        'user_form_id',
        'client_id',
        'contact_name',
        'contact_phone',
        'contact_response',
        'is_converted',
        'url',
        'email'
    ];
    protected $guarded = [];

    public function Client()
    {
        return $this->belongsTo('App\Models\Client','client_id','client_id');
    }
}
