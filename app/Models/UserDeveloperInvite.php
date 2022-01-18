<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserDeveloperInvite extends Model
{
    public $timestamps = true;

    protected $table = 'user_developer_invite';
    protected $primaryKey = 'user_developer_invite_id';

    protected $fillable = [
        'user_id',
        'email',
        'code',
        'status',
        'email_sent'
    ];

    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','user_id');
    }
}
