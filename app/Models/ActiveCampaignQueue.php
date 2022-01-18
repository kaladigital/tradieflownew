<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ActiveCampaignQueue extends Model {

    public $timestamps = true;

    protected $table = 'active_campaign_queue';
    protected $primaryKey = 'active_campaign_queue_id';

    protected $fillable = [
        'user_id',
        'email',
        'action',
        'type',
        'status',
        'rate'
    ];

    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','user_id');
    }
}
