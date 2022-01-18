<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserTradiereviewRedirect extends Model {

    public $timestamps = true;

    protected $table = 'user_tradiereview_redirect';
    protected $primaryKey = 'user_tradiereview_redirect_id';

    protected $fillable = [
        'user_id',
        'code'
    ];

    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','user_id');
    }
}
