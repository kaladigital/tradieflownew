<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserForm extends Model
{
    public $timestamps = true;

    protected $table = 'user_form';
    protected $primaryKey = 'user_form_id';

    protected $fillable = [
        'user_id',
        'website',
        'status',
        'tracking_code',
        'tracking_key',
        'is_manual_tracking'
    ];
    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','user_id');
    }
}
