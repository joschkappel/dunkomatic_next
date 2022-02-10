<?php

namespace App\Checks;

use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;


use Illuminate\Support\Carbon;

class ConcurrentUsersCheck extends Check
{

    protected ?float $failWhenFailedLoginsIsHigherInTheLastMinute = null;
    protected ?float $failWhenFailedLoginsIsHigherInTheLast5Minutes = null;
    protected ?float $failWhenFailedLoginsIsHigherInTheLast15Minutes = null;

    public function failWhenFailedLoginsIsHigherInTheLastMinute(float $failed_logins): self
    {
        $this->failWhenFailedLoginsIsHigherInTheLastMinute = $failed_logins;

        return $this;
    }

    public function failWhenFailedLoginsIsHigherInTheLast5Minutes(float $failed_logins): self
    {
        $this->failWhenFailedLoginsIsHigherInTheLast5Minutes = $failed_logins;

        return $this;
    }

    public function failWhenFailedLoginsIsHigherInTheLast15Minutes(float $failed_logins): self
    {
        $this->failWhenFailedLoginsIsHigherInTheLast15Minutes = $failed_logins;

        return $this;
    }

    public function run(): Result
    {
        $last1min = Carbon::now()->subMinutes(1);
        $last5min = Carbon::now()->subMinutes(5);
        $last15min =  Carbon::now()->subMinutes(15);

        // total logged in users last 1min
        $last1min_tot_cnt = AuthenticationLog::where('login_at','>=', $last1min)->count();
        // total logged in users last 5mins
        $last5min_tot_cnt = AuthenticationLog::where('login_at','>=', $last5min)->count();
        // total logged in users last 15mins
        $last15min_tot_cnt = AuthenticationLog::where('login_at','>=', $last15min)->count();

        // failed logins last 1 min
        $last1min_fail_cnt = AuthenticationLog::where('login_at','>=', $last1min)->where('login_successful',0)->count();
        // failed logins last 5 min
        $last5min_fail_cnt = AuthenticationLog::where('login_at','>=', $last5min)->where('login_successful',0)->count();
        // failed logins last 15 min
        $last15min_fail_cnt = AuthenticationLog::where('login_at','>=', $last15min)->where('login_successful',0)->count();

        $last1min_fail_pct =   $last1min_tot_cnt > 0 ? round( ($last1min_fail_cnt * 100) / $last1min_tot_cnt ,2) : 0;
        $last5min_fail_pct =   $last5min_tot_cnt > 0 ? round( ($last5min_fail_cnt * 100) / $last5min_tot_cnt ,2) : 0;
        $last15min_fail_pct =   $last15min_tot_cnt > 0 ? round( ($last15min_fail_cnt * 100) / $last15min_tot_cnt ,2) : 0;

        $result = Result::make()
            ->ok()
            ->shortSummary(
                "{$last1min_tot_cnt} {$last1min_fail_pct}% / {$last5min_tot_cnt} {$last5min_fail_pct}% / {$last15min_tot_cnt} {$last15min_fail_pct}%"
            )
            ->meta([
                'last_minute' => $last1min_tot_cnt,
                'last_5_minutes' => $last5min_tot_cnt,
                'last_15_minutes' => $last15min_tot_cnt,
                'last_minute_failed' => $last1min_fail_pct,
                'last_5_minutes_failed' => $last5min_fail_pct,
                'last_15_minutes_failed' => $last15min_fail_pct,
            ]);

            if ($this->failWhenFailedLoginsIsHigherInTheLastMinute) {
                if ($last1min_fail_pct > ($this->failWhenFailedLoginsIsHigherInTheLastMinute)) {
                    return $result->failed("The failed login attempts of the last minute is {$last1min_fail_pct}% which is higher than the allowed {$this->failWhenFailedLoginsIsHigherInTheLastMinute}");
                }
            }

            if ($this->failWhenFailedLoginsIsHigherInTheLast5Minutes) {
                if ($last5min_fail_pct > ($this->failWhenFailedLoginsIsHigherInTheLast5Minutes)) {
                    return $result->failed("The failed login attempts of the last five minutes is {$last5min_fail_pct}% which is higher than the allowed {$this->failWhenFailedLoginsIsHigherInTheLast5Minutes}");
                }
            }

            if ($this->failWhenFailedLoginsIsHigherInTheLast15Minutes) {
                if ($last15min_fail_pct > ($this->failWhenFailedLoginsIsHigherInTheLast15Minutes)) {
                    return $result->failed("The failed login attempts of the last fifteen minutes is {$last15min_fail_pct}% which is higher than the allowed {$this->failWhenFailedLoginsIsHigherInTheLast15Minutes}");
                }
            }

            return $result;

    }


}
