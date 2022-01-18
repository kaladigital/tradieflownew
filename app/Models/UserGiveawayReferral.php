<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserGiveawayReferral extends Model
{
    public $timestamps = true;

    protected $table = 'user_giveaway_referral';
    protected $primaryKey = 'user_giveaway_referral_id';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'code',
        'status',
        'months'
    ];
    protected $guarded = [];
}
