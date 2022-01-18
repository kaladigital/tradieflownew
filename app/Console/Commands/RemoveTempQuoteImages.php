<?php

namespace App\Console\Commands;

use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RemoveTempQuoteImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove_temp_quote_images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temp quote images';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 10);
        $files = Storage::disk('quote_temp')->allFiles();
        $yesterday_timestamp = Carbon::now()->addDay(-1)->timestamp;
        foreach ($files as $item) {
            try{
                if (filemtime(public_path('quote_temp/'.$item)) > $yesterday_timestamp) {
                    Storage::disk('quote_temp')->delete($item);
                }
            }
            catch (\Exception $e) {

            }
        }
    }
}
