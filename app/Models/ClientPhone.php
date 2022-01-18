<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ClientPhone extends Model {

    public $timestamps = true;

    protected $table = 'client_phone';
    protected $primaryKey = 'client_phone_id';

    protected $fillable = [
        'client_id',
        'phone',
        'phone_format',
        'country_code',
        'country_number'
    ];

    protected $guarded = [];

    public function Client()
    {
        return $this->belongsTo('App\Models\Client','client_id','client_id');
    }
}
