<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CallHistory extends Model {

    public $timestamps = true;

    protected $table = 'call_history';
    protected $primaryKey = 'call_history_id';

    protected $fillable = [
        'user_id',
        'client_id',
        'phone',
        'type',
        'twilio_call_id',
        'recorded_audio_file',
        'record_status',
        'recorded_playtime_seconds',
        'recorded_playtime_format'
    ];

    protected $guarded = [];

    public function Client()
    {
        return $this->belongsTo('App\Models\Client','client_id','client_id');
    }
}
