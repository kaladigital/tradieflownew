<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Helpers\Helper;
use App\Helpers\NotificationHelper;
use App\Models\EmailQueue;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\TextMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class EventSMSReminderEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event_sms_reminder_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Events SMS Reminder';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 5);
        $sms_global_number = env('SMS_GLOBAL_NUMBER');
        $event_statuses = Constant::GET_EVENT_STATUSES_LIST();
        Event::with('EventLocation')
            ->select('event.*','client.name as client_name','user_twilio_phone.phone','country.name as country_name')
            ->leftJoin('user','user.user_id','=','event.user_id')
            ->leftJoin('country','user.user_id','=','event.user_id')
            ->leftJoin('user_subscription',function($query){
                $query->on('user_subscription.user_id','=','event.user_id')
                    ->where('user_subscription.active','=','1');
            })
            ->leftJoin('client','client.client_id','=','event.client_id')
            ->leftJoin('user_twilio_phone',function($query){
                $query
                    ->on('user_twilio_phone.user_id','event.user_id')
                    ->where('user_twilio_phone.status','=','active');
            })
            ->where(DB::raw('date_format(date_add(event.start_date_time, interval 30 minute),"%Y-%m-%d %H:%i")'),'<',Carbon::now()->format('Y-m-d H:i'))
            ->where('event.has_sms_sent','=','0')
            ->whereNotNull('user_subscription.user_subscription_id')
            ->whereNotNull('client.client_id')
            ->whereNotNull('user_twilio_phone.user_twilio_phone_id')
            ->groupBy('event.event_id')
            ->chunk(1000,function($items) use ($sms_global_number, $event_statuses){
                foreach ($items as $item) {
                    $locations = [];
                    foreach ($item->EventLocation as $event_loc) {
                        $event_location = [];
                        if ($event_loc->address) {
                            $event_location[] = $event_loc->address;
                        }

                        if ($event_loc->zip) {
                            $event_location[] = $event_loc->zip;
                        }

                        if ($event_loc->city) {
                            $event_location[] = $event_loc->city;
                        }

                        if ($item->country_name) {
                            $event_location[] = $item->country_name;
                        }

                        if ($event_location) {
                            $locations[] = implode(', ',$event_location);
                        }
                    }

                    /**Create Message*/
                    switch ($item->status) {
                        case 'quote-meeting':
                        case 'work-in-progress':
                            $message = 'Reminder: You event with '.$item->client_name.' starts in 30 minutes'."\r\n".
                                ($item->other_status_text ? $item->other_status_text.' - ' : '').$event_statuses[$item->status]."\r\n".
                            'Date: '.Helper::generateEventStartEndDateFormat($item->start_date_time, $item->end_date_time)."\r\n".
                            ($locations ? 'Location: '.implode("\r\n",$locations) : '');
                        break;
                        case 'remind-me':
                            $message = 'Reminder: You event with '.$item->client_name.' starts in 30 minutes'."\r\n".
                                ($item->other_status_text ? $item->other_status_text.' - ' : '')."\r\n".
                                'Follow-Up Event'."\r\n".
                                'Date: '.Helper::generateEventStartEndDateFormat($item->start_date_time, $item->end_date_time)."\r\n".
                                ($locations ? 'Location: '.implode("\r\n",$locations) : '');
                        break;
                        case 'other':
                            $message = 'Your event starts in 30 minutes'."\r\n".
                                ($item->other_status_text ? $item->other_status_text.' - ' : '')."\r\n".
                                $event_statuses[$item->status]."\r\n".
                                'Date: '.Helper::generateEventStartEndDateFormat($item->start_date_time, $item->end_date_time)."\r\n".
                                ($locations ? 'Location: '.implode("\r\n",$locations) : '');
                        break;
                    }

                    $model = new TextMessage();
                    $model->user_id = $item->user_id;
                    $model->client_id = $item->client_id;
                    $model->message = $message;
                    $model->from_number = $sms_global_number;
                    $model->to_number = $item->phone;
                    $model->has_read = '0';
                    $model->client_sent = '1';
                    $model->save();

                    /**Update sent status*/
                    $item->has_sms_sent = '1';
                    $item->update();
                }
            });
    }
}
