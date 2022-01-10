<?php

namespace App\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class DbConnectionsCheck extends Check
{
    public function run(): Result
    {

        $available_connections = $this->getAvailableConnectionsPercentage();
        Log::debug('DB connections availability',['%'=>$available_connections]);

        $result = Result::make()
            ->meta(['available_db_connections_percentage' => $available_connections])
            ->shortSummary($available_connections . '%');

        if ($available_connections > 90){
            return $result->failed("Almost no connections left ({$available_connections}% used)");
        }
        if ($available_connections > 70){
            return $result->warning("Connections are getting used up ({$available_connections}% used)");
        }
        return $result->ok();

    }

    protected function getAvailableConnectionsPercentage(){
        $gVariables = DB::select('show global variables');
        $gStatus = DB::select('show global status');

        $max_connections = intval(Arr::first($gVariables, function( $v, $k) {
            return $v->Variable_name == 'max_connections';
        })->Value);

        $connections = intval(Arr::first($gStatus, function( $v, $k) {
            return $v->Variable_name == 'Threads_connected';
        })->Value);

        return round( 100 * $connections / $max_connections, 2);

    }
}
