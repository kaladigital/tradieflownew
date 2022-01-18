<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TextMessage extends Model
{
    public $timestamps = true;

    protected $table = 'text_message';
    protected $primaryKey = 'text_message_id';

    protected $fillable = [
        'user_id',
        'client_id',
        'message',
        'from_number',
        'to_number',
        'has_read',
        'client_sent',
        'twilio_sid'
    ];
    protected $guarded = [];

    public function TextMessageMedia()
    {
        return $this->hasMany('App\Models\TextMessageMedia','text_message_id','text_message_id');
    }
}
