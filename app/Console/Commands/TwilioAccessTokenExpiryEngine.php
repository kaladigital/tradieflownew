<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Models\UserDevice;
use App\Models\UserSubscription;
use App\Models\UserTwilioPhone;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class TwilioAccessTokenExpiryEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twilio_access_token_expire_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Twilio access token expire';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 10);
        $today_date_time = Carbon::now()->format('Y-m-d H:i:s');
        UserDevice::select('user_device.*')
            ->leftJoin('user','user.user_id','=','user_device.user_id')
            ->where(DB::raw('date_add(user_device.twilio_expiry_date_time, interval 1 hour)'),'<=',$today_date_time)
            ->where('user.active','=','1')
            ->chunk(1000,function($items) {
                foreach ($items as $item) {
                    if ($item->device_token) {
                        try{
                            $messageText = json_encode([
                                'twilio_access_token_expired' => true
                            ]);

                            $options = [
                                'key_id' => env('APPLE_PUSH_KEY_ID'),
                                'team_id' => env('APPLE_PUSH_TEAM_ID'),
                                'app_bundle_id' => env('APPLE_APP_BUNDLE_ID'),
                                'certificate_path' => storage_path('app/creds/pedestal_production.pem'),
                                'certificate_secret' => null
                            ];

                            $authProvider = \Pushok\AuthProvider\Certificate::create($options);
                            $alert = \Pushok\Payload\Alert::create()->setTitle('');
                            $alert = $alert->setBody($messageText);

                            $payload = \Pushok\Payload::create()->setAlert($alert);
                            $payload->setContentAvailability(true);
                            $payload->setPushType('background');
                            $payload->setSound('default');

                            $notifications = [new \Pushok\Notification($payload,$item->device_token)];
                            $client = new \Pushok\Client($authProvider, $production = true);
                            $client->addNotifications($notifications);
                            $client->push();
                        }
                        catch (\Exception $e) {

                        }
                    }
                }
            });
    }
}
