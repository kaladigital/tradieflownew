<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ClientLocation extends Model {

    public $timestamps = true;

    protected $table = 'client_location';
    protected $primaryKey = 'client_location_id';

    protected $fillable = [
        'client_id',
        'city',
        'zip',
        'address',
        'location_type'
    ];

    protected $guarded = [];
}
