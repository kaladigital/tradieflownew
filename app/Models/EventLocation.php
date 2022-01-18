<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EventLocation extends Model {

    public $timestamps = true;

    protected $table = 'event_location';
    protected $primaryKey = 'event_location_id';

    protected $fillable = [
        'event_id',
        'city',
        'zip',
        'address'
    ];

    protected $guarded = [];
}
