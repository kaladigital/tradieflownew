<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserIndustry extends Model
{
    public $timestamps = true;

    protected $table = 'user_industry';
    protected $primaryKey = 'user_industry_id';

    protected $fillable = [
        'user_id',
        'industry_id'
    ];
    protected $guarded = [];
}
