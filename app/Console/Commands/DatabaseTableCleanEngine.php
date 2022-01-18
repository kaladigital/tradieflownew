<?php

namespace App\Console\Commands;

use App\Models\TextMessageMedia;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseTableCleanEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database_table_clean_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove not used items';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 5);
        TextMessageMedia::select('text_message_media.*')
            ->leftJoin('text_message','text_message.text_message_id','=','text_message_media.text_message_id')
            ->whereNull('text_message.text_message_id')
            ->chunk(1000,function($items){
                foreach ($items as $item) {
                    if (Storage::disk('text_media')->exists($item->file_name)) {
                        Storage::disk('text_media')->delete($item->file_name);
                    }

                    $item->delete();
                }
            });
    }
}
