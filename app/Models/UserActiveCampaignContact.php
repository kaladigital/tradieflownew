<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class UserActiveCampaignContact extends Authenticatable
{
    public $timestamps = true;

    protected $table = 'user_active_campaign_contact';
    protected $primaryKey = 'user_active_campaign_contact_id';

    protected $fillable = [
        'user_id',
        'active_campaign_contact_id',
        'tradie_flow_deal_id',
        'tradie_reviews_deal_id'
    ];
}
