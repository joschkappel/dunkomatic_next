<?php

namespace App\Checks;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

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
        Artisan::call('queue:monitor default,janitor,exports,region_1,region_2,region_3,region_4,region_5');
        $output = Str::of(Artisan::output())->explode(PHP_EOL);
        $q_default = Str::of($output[2])->explode(' ');
        $q_janitor = Str::of($output[3])->explode(' ');
        $q_exports = Str::of($output[4])->explode(' ');
        $q_region_1 = Str::of($output[5])->explode(' ');
        $q_region_2 = Str::of($output[6])->explode(' ');
        $q_region_3 = Str::of($output[7])->explode(' ');
        $q_region_4 = Str::of($output[8])->explode(' ');
        $q_region_5 = Str::of($output[9])->explode(' ');

        $q_def_status = trim($q_default[5]);
        $q_def_name = trim($q_default[2]);
        $q_def_size = trim(Str::between($q_default[4], '[', ']'));
        $q_jan_status = trim($q_janitor[5]);
        $q_jan_name = trim($q_janitor[2]);
        $q_jan_size = trim(Str::between($q_janitor[4], '[', ']'));
        $q_exp_status = trim($q_exports[5]);
        $q_exp_name = trim($q_exports[2]);
        $q_exp_size = trim(Str::between($q_exports[4], '[', ']'));
        $q_r1_status = trim($q_region_1[5]);
        $q_r1_name = trim($q_region_1[2]);
        $q_r1_size = trim(Str::between($q_region_1[4], '[', ']'));

        $q_r2_status = trim($q_region_2[5]);
        $q_r2_name = trim($q_region_2[2]);
        $q_r2_size = trim(Str::between($q_region_2[4], '[', ']'));

        $q_r3_status = trim($q_region_3[5]);
        $q_r3_name = trim($q_region_3[2]);
        $q_r3_size = trim(Str::between($q_region_3[4], '[', ']'));

        $q_r4_status = trim($q_region_4[5]);
        $q_r4_name = trim($q_region_4[2]);
        $q_r4_size = trim(Str::between($q_region_4[4], '[', ']'));

        $q_r5_status = trim($q_region_5[5]);
        $q_r5_name = trim($q_region_5[2]);
        $q_r5_size = trim(Str::between($q_region_5[4], '[', ']'));

        $result = Result::make()
            ->ok()
            ->shortSummary(
                "{$failed_jobs}
                   / {$q_def_status} {$q_def_size}
                   / {$q_jan_status} {$q_jan_size}
                   / {$q_exp_status} {$q_exp_size}
                   / {$q_r1_status} {$q_r1_size}
                   / {$q_r2_status} {$q_r2_size}
                   / {$q_r3_status} {$q_r3_size}
                   / {$q_r4_status} {$q_r4_size}
                   / {$q_r5_status} {$q_r5_size}"
            )
            ->meta([
                'failed_jobs' => $failed_jobs,
                'default_status' => $q_def_status,
                'default_size' => $q_def_size,
                'janitor_status' => $q_jan_status,
                'janitor_size' => $q_jan_size,
                'exports_status' => $q_exp_status,
                'exports_size' => $q_exp_size,
                'region_1_status' => $q_r1_status,
                'region_1_size' => $q_r1_size,
                'region_2_status' => $q_r2_status,
                'region_2_size' => $q_r2_size,
                'region_3_status' => $q_r3_status,
                'region_3_size' => $q_r3_size,
                'region_4_status' => $q_r4_status,
                'region_4_size' => $q_r4_size,
                'region_5_status' => $q_r5_status,
                'region_5_size' => $q_r5_size,
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
            if ($q_r1_size > ($this->failWhenQueueLengthIsHigher)) {
                return $result->failed("The {$q_r1_name} queue size is {$q_r1_size}% which is higher than the allowed {$this->failWhenQueueLengthIsHigher}");
            }
            if ($q_r2_size > ($this->failWhenQueueLengthIsHigher)) {
                return $result->failed("The {$q_r2_name} queue size is {$q_r2_size}% which is higher than the allowed {$this->failWhenQueueLengthIsHigher}");
            }
            if ($q_r3_size > ($this->failWhenQueueLengthIsHigher)) {
                return $result->failed("The {$q_r3_name} queue size is {$q_r3_size}% which is higher than the allowed {$this->failWhenQueueLengthIsHigher}");
            }
            if ($q_r4_size > ($this->failWhenQueueLengthIsHigher)) {
                return $result->failed("The {$q_r4_name} queue size is {$q_r4_size}% which is higher than the allowed {$this->failWhenQueueLengthIsHigher}");
            }
            if ($q_r5_size > ($this->failWhenQueueLengthIsHigher)) {
                return $result->failed("The {$q_r5_name} queue size is {$q_r5_size}% which is higher than the allowed {$this->failWhenQueueLengthIsHigher}");
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
        if ($q_r1_status != 'OK') {
            return $result->warning("The {$q_r1_name} queue with size {$q_r1_size} is {$q_r1_status}");
        }
        if ($q_r2_status != 'OK') {
            return $result->warning("The {$q_r2_name} queue with size {$q_r2_size} is {$q_r2_status}");
        }
        if ($q_r3_status != 'OK') {
            return $result->warning("The {$q_r3_name} queue with size {$q_r3_size} is {$q_r3_status}");
        }
        if ($q_r4_status != 'OK') {
            return $result->warning("The {$q_r4_name} queue with size {$q_r4_size} is {$q_r4_status}");
        }
        if ($q_r5_status != 'OK') {
            return $result->warning("The {$q_r5_name} queue with size {$q_r5_size} is {$q_r5_status}");
        }

        return $result;
    }
}
