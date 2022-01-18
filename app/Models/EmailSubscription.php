<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EmailSubscription extends Model
{
    public $timestamps = true;

    protected $table = 'email_subscription';
    protected $primaryKey = 'email_subscription_id';

    protected $fillable = [
        'user_id',
        'email',
        'product'
    ];
    protected $guarded = [];
}
