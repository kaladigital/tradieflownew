<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ClientHistory extends Model {

    public $timestamps = true;

    protected $table = 'client_history';
    protected $primaryKey = 'client_history_id';

    protected $fillable = [
        'client_id',
        'related_id',
        'title',
        'description',
        'start_date_time',
        'end_date_time',
        'type'
    ];

    protected $guarded = [];
}
