<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Client extends Model {

    public $timestamps = true;

    protected $table = 'client';
    protected $primaryKey = 'client_id';

    protected $fillable = [
        'user_id',
        'status',
        'name',
        'email',
        'company',
        'quote_meeting_date_time',
        'work_started_date_time',
        'notes',
        'xero_id'
    ];

    protected $guarded = [];

    public function ClientValue()
    {
        return $this->hasMany('App\Models\ClientValue','client_id','client_id');
    }

    public function ClientLocation()
    {
        return $this->hasMany('App\Models\ClientLocation','client_id','client_id');
    }

    public function ClientPhone()
    {
        return $this->hasMany('App\Models\ClientPhone','client_id','client_id');
    }

    public function ClientLastValue()
    {
        return $this->belongsTo('App\Models\ClientValue','client_id','client_id')
            ->orderBy('client_value_id','desc');
    }

    public function ClientNote()
    {
        return $this->hasMany('App\Models\ClientNote','client_id','client_id');
    }

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','user_id');
    }
}
