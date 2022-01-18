<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Models\CallHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TwilioRecordingProcessEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twilio_recording_process_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download audio calls';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 5);
        $twilio = new \Twilio\Rest\Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
        CallHistory::whereIn('type',['outgoing','incoming'])
            ->whereNull('recorded_audio_file')
            ->where('record_status','=','pending')
            ->whereNotNull('twilio_call_id')
            ->chunk(1000,function($items) use ($twilio) {
                foreach ($items as $item) {
                    try{
                        $recording = $twilio->calls($item->twilio_call_id)
                            ->recordings
                            ->read([],1);

                        $recording_url = str_replace('.json','.mp3',Constant::GET_TWILO_API_URL().'/'.$recording['0']->uri);
                        $mp3_file = Helper::getTwilioRecording($recording_url);

                        if ($mp3_file) {
                            $item->record_status = 'completed';
                            $item->recorded_audio_file = md5($item->twilio_call_id.uniqid()).'.mp3';
                            Storage::disk('records')->put($item->recorded_audio_file,$mp3_file);

                            /**Get MP3 duration*/
                            try{
                                $getID3 = new \getID3();
                                $mp3_details = $getID3->analyze(public_path('records/'.$item->recorded_audio_file));
                                $item->recorded_playtime_seconds = $mp3_details['playtime_seconds'];
                                $item->recorded_playtime_format = $mp3_details['playtime_string'];
                            }
                            catch (\Exception $e) {

                            }
                        }
                        else{
                            $item->record_status = 'fail';
                        }

                        $item->update();
                    }
                    catch (\Exception $e) {

                    }
                }
            });

        echo 'done';
    }
}
