<?php

namespace App\Console\Commands;

use App\Helpers\XeroHelper;
use App\Models\User;
use App\Models\UserXeroAccount;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class XeroAccessTokenUpdateEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xero_access_token_update_engine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Xero access token';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 5);
        UserXeroAccount::where(DB::raw('date_add(updated_at,interval 1 day)'), '<=', Carbon::now()->format('Y-m-d H:i:s'))
            ->chunk(1000,function($items){
                foreach ($items as $item) {
                    try{
                        XeroHelper::getXeroInstance($item);
                    }
                    catch (\Exception $e) {

                    }
                }
            });
    }
}
