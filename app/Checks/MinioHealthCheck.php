<?php

namespace App\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

use Illuminate\Support\Facades\Http;

class MinioHealthCheck extends Check
{
    public function run(): Result
    {

        $url = 'http://minio:9000/minio/health/live';
        $response = Http::get($url);


        $result = Result::make()
            ->meta(['health' => $response->status() ])
            ->shortSummary( 'OK' );

        if ($response->successful()){
            return $result->ok();
        } else {
            return $result->failed("Minio Server not healthy!");
        }

    }

}
