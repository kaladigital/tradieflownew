<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserAdminNote extends Model
{
    public $timestamps = true;

    protected $table = 'user_admin_note';
    protected $primaryKey = 'user_admin_note_id';

    protected $fillable = [
        'user_id',
        'note',
        'status'
    ];

    protected $guarded = [];
}
