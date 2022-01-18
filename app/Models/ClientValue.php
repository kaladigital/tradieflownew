<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ClientValue extends Model {

    public $timestamps = true;

    protected $table = 'client_value';
    protected $primaryKey = 'client_value_id';

    protected $fillable = [
        'client_id',
        'upfront_value',
        'ongoing_value',
        'project_name',
        'status',
        'unique_code'
    ];

    protected $guarded = [];

    public function Client() {
        return $this->belongsTo('App\Models\Client','client_id','client_id');
    }
}
