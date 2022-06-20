<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class ExportStatistics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        // collect data

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = array();

        $readdate = Carbon::yesterday();
        $data[] = $readdate->isoFormat('L');
        $data[] = User::whereDay('approved_at', $readdate)->count();
        $data[] = User::whereDay('rejected_at', $readdate)->count();

        $oauth_providers = User::whereDay('created_at', $readdate)->get()->countBy('provider');
        foreach( ['','google','facebook','twitter'] as $provider){
            $data[] = $oauth_providers[$provider] ?? 0;
        }
        $data[] = DB::table('audits')
        ->whereDate('created_at', $readdate)
        ->count();

        $handle = fopen(storage_path("exports/user_stats.csv"),"a");

        fputcsv($handle, $data , ',');

        fclose($handle);
        Log::notice('[JOB EXPORT STATS] user data appended');

    }
}
