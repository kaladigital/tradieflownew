<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ClientNote extends Model {

    public $timestamps = true;

    protected $table = 'client_note';
    protected $primaryKey = 'client_note_id';

    protected $fillable = [
        'client_id',
        'note'
    ];

    protected $guarded = [];
}
