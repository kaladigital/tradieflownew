<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserReferralCode extends Model
{
    public $timestamps = true;

    protected $table = 'user_referral_code';
    protected $primaryKey = 'user_referral_code_id';

    protected $fillable = [
        'user_id',
        'referral_code',
        'type'
    ];
    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','user_id');
    }
}
