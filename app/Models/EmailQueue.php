<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailQueue extends Model
{
    public $timestamps = true;

    protected $table = 'email_queue';
    protected $primaryKey = 'email_queue_id';

    protected $fillable = [
        'target',
        'type',
        'subject',
        'message',
        'status'
    ];
}
