<?php

namespace App\Console\Commands;

use App\Helpers\NotificationHelper;
use App\Models\User;
use App\Models\UserDeveloperInvite;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UserDeveloperInviteEmailEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user_developer_invite_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out emails to developers after 1 hour';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 5);
        set_time_limit(60 * 60 * 10);
        UserDeveloperInvite::select('user_developer_invite.user_developer_invite_id')
            ->leftJoin('user','user.user_id','=','user_developer_invite.user_id')
            ->leftJoin('user_subscription',function($query){
                $query->on('user_subscription.user_id','=','user_developer_invite.user_id')
                    ->where('user_subscription.type','=','tradieflow')
                    ->where('user_subscription.active','=','1');
            })
            ->where('user_developer_invite.email_sent','=','0')
            ->where(DB::raw('date_add(user_developer_invite.created_at, interval 1 hour)'),'<',Carbon::now()->format('Y-m-d H:i:s'))
            ->whereNotNull('user_subscription.user_subscription_id')
            ->groupBy('user_developer_invite.user_developer_invite_id')
            ->chunk(1000,function($items){
                foreach ($items as $item) {
                    /**Double check to avoid duplicates in case forms emails already sent*/
                    $user_developer_invite = UserDeveloperInvite::with('User')->find($item->user_developer_invite_id);
                    if ($user_developer_invite && !$user_developer_invite->email_sent) {
                        $user_developer_invite->email_sent = '1';
                        $user_developer_invite->update();

                        /**Send email*/
                        NotificationHelper::developerInvite($user_developer_invite->code,$user_developer_invite->User->name,$user_developer_invite->email);
                    }
                }
            });
    }
}
