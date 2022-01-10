<?php

namespace App\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class DbConnectionsCheck extends Check
{
    public function run(): Result
    {


        $result = Result::make();
        $result->shortSummary("DB connection availbility");

        return $result->ok();
    }
}
