<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    public $timestamps = true;

    protected $table = 'notification_log';
    protected $primaryKey = 'notification_log_id';

    protected $fillable = [];
    protected $guarded = [];
}
