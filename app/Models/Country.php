<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = true;

    protected $table = 'country';
    protected $primaryKey = 'country_id';

    protected $fillable = [
        'name',
        'number',
        'code',
        'is_twilio',
        'lat',
        'lng'
    ];
}
