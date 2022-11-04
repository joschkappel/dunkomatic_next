<?php

namespace App\Checks;

use App\Models\User;
use Illuminate\Support\Carbon;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class RegistrationZombiesCheck extends Check
{
    protected ?float $failWhenZombieCountIsHigherInTheLastHour = null;

    protected ?float $failWhenZombieCountIsHigherInTheLastDay = null;

    public function failWhenZombieCountIsHigherInTheLastHour(float $zombie_count): self
    {
        $this->failWhenZombieCountIsHigherInTheLastHour = $zombie_count;

        return $this;
    }

    public function failWhenZombieCountIsHigherInTheLastDay(float $zombie_count): self
    {
        $this->failWhenZombieCountIsHigherInTheLastDay = $zombie_count;

        return $this;
    }

    public function run(): Result
    {
        $lastHour = Carbon::now()->subHour();
        $lastDay = Carbon::now()->subDay();

        $user_query = User::whereNull('approved_at')->whereNull('reason_join');
        // total registration zombies last hour
        $lastHour_tot_cnt = $user_query->where('created_at', '>=', $lastHour)->count();
        // total registration zombies last day
        $lastDay_tot_cnt = $user_query->where('created_at', '>=', $lastDay)->count();

        $result = Result::make()
            ->ok()
            ->shortSummary(
                "{$lastHour_tot_cnt} / {$lastDay_tot_cnt}"
            )
            ->meta([
                'last_hour' => $lastHour_tot_cnt,
                'last_day' => $lastDay_tot_cnt,
            ]);

        if ($this->failWhenZombieCountIsHigherInTheLastHour) {
            if ($lastHour_tot_cnt > ($this->failWhenZombieCountIsHigherInTheLastHour)) {
                return $result->failed("The number of zombie registrations of the last minute is {$lastHour_tot_cnt} which is higher than the allowed {$this->failWhenZombieCountIsHigherInTheLastHour}");
            }
        }

        if ($this->failWhenZombieCountIsHigherInTheLastDay) {
            if ($lastDay_tot_cnt > ($this->failWhenZombieCountIsHigherInTheLastDay)) {
                return $result->failed("The number of zombie registrations of the last five minutes is {$lastDay_tot_cnt} which is higher than the allowed {$this->failWhenZombieCountIsHigherInTheLastDay}");
            }
        }

        return $result;
    }
}
