<?php

namespace App\Console\Commands;

use App\Helpers\NotificationHelper;
use App\Models\EmailQueue;
use Illuminate\Console\Command;

class EmailQueueProcessEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email_queue_process_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email queue process engine';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 5);
        EmailQueue::chunk(1000,function($items){
            foreach ($items as $item) {
                if ($item->type == 'email') {
                    NotificationHelper::SendRawHtmlEmailMessage('Leave a Review', $item->message, $item->target);
                    $item->delete();
                }
            }
        });
        return true;
    }
}
