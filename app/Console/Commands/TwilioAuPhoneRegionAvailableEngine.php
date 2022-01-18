<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Models\CallHistory;
use App\Models\TwilioAuPhoneRegion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TwilioAuPhoneRegionAvailableEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twilio_au_phone_region_availability_check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if we have an available mobile, landing or toll-free for those regions';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 5);
        $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
        TwilioAuPhoneRegion::chunk(1000,function($items) use ($twilio){
            foreach ($items as $item) {
                $phone_number = $item->region_code.'********';
                $item->has_mobile = empty($twilio->availablePhoneNumbers('AU')->mobile->read(["contains" => $phone_number, "voiceEnabled" => true], 1)) ? '0' : '1';
                $item->has_local = empty($twilio->availablePhoneNumbers('AU')->local->read(["contains" => $phone_number, "voiceEnabled" => true], 1)) ? '0' : '1';
                $item->has_toll_free = empty($twilio->availablePhoneNumbers('AU')->tollFree->read(["contains" => $phone_number, "voiceEnabled" => true], 1)) ? '0' : '1';
                $item->update();
            }
        });
    }
}
