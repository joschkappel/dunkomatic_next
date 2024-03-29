<?php

namespace App\Checks;

use Illuminate\Support\Facades\Http;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class LaravelEchoServerCheck extends Check
{
    public function run(): Result
    {
        $echo_appid = 'dunkomatic';
        $echo_appkey = '44fad1c68075f07b8d72c8beaf59042d';

        $url = 'http://laravel-echo-server:6001/apps/'.$echo_appid.'/status';
        $response = Http::get($url, ['auth_key' => $echo_appkey]);
        $uptime = strval(round($response->object()->uptime, 2)).'s';
        $subscription_count = strval($response->object()->subscription_count);
        $memory_usage = strval(round(($response->object()->memory_usage->heapUsed * 100) / $response->object()->memory_usage->rss)).'%';

        $result = Result::make()
            ->meta(['uptime' => $uptime])
            ->shortSummary($subscription_count.'  '.$uptime.'  '.$memory_usage);

        if ($response->successful()) {
            if ((($response->object()->memory_usage->heapUsed * 100) / $response->object()->memory_usage->rss) > 70) {
                return $result->warning('Memory usage is greater than 70% !');
            } elseif ((($response->object()->memory_usage->heapUsed * 100) / $response->object()->memory_usage->rss) > 90) {
                return $result->failed('Memory usage is greater than 90% !');
            } else {
                return $result->ok();
            }
        } else {
            return $result->failed('Laravel Echo Server not available!');
        }
    }
}
