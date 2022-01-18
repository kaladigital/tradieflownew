<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    public $timestamps = true;

    protected $table = 'user_notification';
    protected $primaryKey = 'user_notification_id';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'has_read',
        'url',
        'type',
        'status',
        'product'
    ];
    protected $guarded = [];
}
