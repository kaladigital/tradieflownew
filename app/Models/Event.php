<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Event extends Model {

    public $timestamps = true;

    protected $table = 'event';
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'user_id',
        'client_id',
        'phone',
        'start_date_time',
        'end_date_time',
        'status',
        'client_name',
        'upfront_value',
        'ongoing_value',
        'other_status_text',
        'has_sms_sent'
    ];

    protected $guarded = [];

    public function Client(){
        return $this->belongsTo('App\Models\Client','client_id','client_id');
    }

    public function EventLocation()
    {
        return $this->hasMany('App\Models\EventLocation','event_id','event_id');
    }
}
