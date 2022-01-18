<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserTask extends Model {

    public $timestamps = true;

    protected $table = 'user_task';
    protected $primaryKey = 'user_task_id';

    protected $fillable = [
        'user_id',
        'client_id',
        'title',
        'description',
        'status'
    ];

    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','user_id');
    }
}
