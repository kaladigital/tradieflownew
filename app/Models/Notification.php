<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

    public $timestamps = true;

    protected $table = 'notification';
    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'name',
        'object_name',
        'subject',
        'body',
        'active',
        'variables'
    ];

    protected $guarded = [];
}
