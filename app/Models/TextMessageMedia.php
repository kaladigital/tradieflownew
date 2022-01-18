<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TextMessageMedia extends Model
{
    public $timestamps = true;

    protected $table = 'text_message_media';
    protected $primaryKey = 'text_message_media_id';

    protected $fillable = [
        'text_message_id',
        'file_name'
    ];
    protected $guarded = [];
}
