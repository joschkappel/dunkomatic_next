<?php

namespace App\Checks;

use App\Models\User;
use Illuminate\Support\Carbon;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class RegistrationZombiesCheck extends Check
{
    protected ?float $failWhenZombieCountIsHigherInTheLastMinute = null;

    protected ?float $failWhenZombieCountIsHigherInTheLast5Minutes = null;

    protected ?float $failWhenZombieCountIsHigherInTheLast15Minutes = null;

    public function failWhenZombieCountIsHigherInTheLastMinute(float $zombie_count): self
    {
        $this->failWhenZombieCountIsHigherInTheLastMinute = $zombie_count;

        return $this;
    }

    public function failWhenZombieCountIsHigherInTheLast5Minutes(float $zombie_count): self
    {
        $this->failWhenZombieCountIsHigherInTheLast5Minutes = $zombie_count;

        return $this;
    }

    public function failWhenZombieCountIsHigherInTheLast15Minutes(float $zombie_count): self
    {
        $this->failWhenZombieCountIsHigherInTheLast15Minutes = $zombie_count;

        return $this;
    }

    public function run(): Result
    {
        $last1min = Carbon::now()->subMinutes(1);
        $last5min = Carbon::now()->subMinutes(5);
        $last15min = Carbon::now()->subMinutes(15);

        $user_query = User::whereNull('approved_at')->whereNull('reason_join');
        // total registration zombies last 1min
        $last1min_tot_cnt = $user_query->where('created_at', '>=', $last1min)->count();
        // total registration zombies last 5mins
        $last5min_tot_cnt = $user_query->where('created_at', '>=', $last5min)->count();
        // total registration zombies last 15mins
        $last15min_tot_cnt = $user_query->where('created_at', '>=', $last15min)->count();

        $result = Result::make()
            ->ok()
            ->shortSummary(
                "{$last1min_tot_cnt} / {$last5min_tot_cnt} / {$last15min_tot_cnt}"
            )
            ->meta([
                'last_minute' => $last1min_tot_cnt,
                'last_5_minutes' => $last5min_tot_cnt,
                'last_15_minutes' => $last15min_tot_cnt,
            ]);

        if ($this->failWhenZombieCountIsHigherInTheLastMinute) {
            if ($last1min_tot_cnt > ($this->failWhenZombieCountIsHigherInTheLastMinute)) {
                return $result->failed("The number of zombie registrations of the last minute is {$last1min_tot_cnt} which is higher than the allowed {$this->failWhenZombieCountIsHigherInTheLastMinute}");
            }
        }

        if ($this->failWhenZombieCountIsHigherInTheLast5Minutes) {
            if ($last5min_tot_cnt > ($this->failWhenZombieCountIsHigherInTheLast5Minutes)) {
                return $result->failed("The number of zombie registrations of the last five minutes is {$last5min_tot_cnt} which is higher than the allowed {$this->failWhenZombieCountIsHigherInTheLast5Minutes}");
            }
        }

        if ($this->failWhenZombieCountIsHigherInTheLast15Minutes) {
            if ($last15min_tot_cnt > ($this->failWhenZombieCountIsHigherInTheLast15Minutes)) {
                return $result->failed("The number of zombie registrations of the last fifteen minutes is {$last15min_tot_cnt} which is higher than the allowed {$this->failWhenZombieCountIsHigherInTheLast15Minutes}");
            }
        }

        return $result;
    }
}
