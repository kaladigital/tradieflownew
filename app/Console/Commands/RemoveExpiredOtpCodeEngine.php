<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveExpiredOtpCodeEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove_expired_otp_codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired otp codes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(60 * 60 * 5);
        User::whereNotNull('otp_created_date')
            ->where(DB::raw('date_add(otp_created_date, interval 2 hour)'),'<=',Carbon::now()->format('Y-m-d H:i:s'))
            ->update([
                'otp_created_date' => null,
                'otp_code' => null
            ]);

        User::whereNotNull('remember_token')
            ->where(DB::raw('date_add(updated_at, interval 2 hour)'),'<=',Carbon::now()->format('Y-m-d H:i:s'))
            ->update([
                'remember_token' => null
            ]);
        return true;
    }
}
