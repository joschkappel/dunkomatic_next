<?php

namespace App\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class QueueLoadCheck extends Check
{

    protected ?float $failWhenFailedJobsIsHigher = null;
    protected ?float $failWhenQueueLengthIsHigher = null;

    public function failWhenFailedJobsIsHigher(float $failed_jobs): self
    {
        $this->failWhenFailedJobsIsHigher = $failed_jobs;

        return $this;
    }

    public function failWhenQueueLengthIsHigher(float $queue_length): self
    {
        $this->failWhenQueueLengthIsHigher = $queue_length;

        return $this;
    }

    public function run(): Result
    {
        Artisan::call('queue:failed');
        $failed_jobs = Str::of(Artisan::output())->explode(PHP_EOL)->count();
        $failed_jobs = ($failed_jobs > 5) ? $failed_jobs - 5 : 0;  // 5 lines go to header/separator/etc
        Artisan::call('queue:monitor default,janitor,exports');
        $output = Str::of(Artisan::output())->explode(PHP_EOL);
        $q_default = Str::of($output[2])->explode(' ');
        $q_janitor = Str::of($output[3])->explode(' ');
        $q_exports = Str::of($output[4])->explode(' ');

        $q_def_status = trim($q_default[5]);
        $q_def_name = trim($q_default[2]);
        $q_def_size = trim(Str::between($q_default[4],'[',']'));
        $q_jan_status = trim($q_janitor[5]);
        $q_jan_name = trim($q_janitor[2]);
        $q_jan_size = trim(Str::between($q_janitor[4],'[',']'));
        $q_exp_status = trim($q_exports[5]);
        $q_exp_name = trim($q_exports[2]);
        $q_exp_size = trim(Str::between($q_exports[4],'[',']'));


        $result = Result::make()
            ->ok()
            ->shortSummary(
                "{$failed_jobs}
                   / {$q_def_name}: {$q_def_status} {$q_def_size}
                   / {$q_jan_name}: {$q_jan_status} {$q_jan_size}
                   / {$q_exp_name}: {$q_exp_status} {$q_exp_size}"
            )
            ->meta([
                'failed_jobs' => $failed_jobs,
                'default_status' => $q_def_status,
                'default_size' => $q_def_size,
                'janitor_status' => $q_jan_status,
                'janitor_size' => $q_jan_size,
                'exports_status' => $q_exp_status,
                'exports_size' => $q_exp_size,
            ]);

            if ($this->failWhenFailedJobsIsHigher) {
                if ($failed_jobs > ($this->failWhenFailedJobsIsHigher)) {
                    return $result->failed("The failed jobs count is {$failed_jobs} which is higher than the allowed {$this->failWhenFailedJobsIsHigher}");
                }
            }

            if ($this->failWhenQueueLengthIsHigher) {
                if ($q_def_size > ($this->failWhenQueueLengthIsHigher)) {
                    return $result->failed("The {$q_def_name} queue size is {$q_def_size}% which is higher than the allowed {$this->failWhenQueueLengthIsHigher}");
                }
                if ($q_jan_size > ($this->failWhenQueueLengthIsHigher)) {
                    return $result->failed("The {$q_jan_name} queue size is {$q_jan_size}% which is higher than the allowed {$this->failWhenQueueLengthIsHigher}");
                }
                if ($q_exp_size > ($this->failWhenQueueLengthIsHigher)) {
                    return $result->failed("The {$q_exp_name} queue size is {$q_exp_size}% which is higher than the allowed {$this->failWhenQueueLengthIsHigher}");
                }
            }
            if ($q_def_status != 'OK') {
                return $result->warning("The {$q_def_name} queue with size {$q_def_size} is {$q_def_status}");
            }
            if ($q_jan_status != 'OK') {
                return $result->warning("The {$q_jan_name} queue with size {$q_jan_size} is {$q_jan_status}");
            }
            if ($q_exp_status != 'OK') {
                return $result->warning("The {$q_exp_name} queue with size {$q_exp_size} is {$q_exp_status}");
            }

            return $result;

    }


}
