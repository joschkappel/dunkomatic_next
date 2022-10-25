<?php

namespace App\Checks;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class DuplicateMemberCheck extends Check
{
    protected ?float $failWhenDuplicatesIsHigher = null;

    public function failWhenDuplicatesIsHigher(float $duplicates): self
    {
        $this->failWhenDuplicatesIsHigher = $duplicates;

        return $this;
    }

    public function run(): Result
    {
        Artisan::call('dmatic:memberstats');
        $duplicate_lines = Str::of(Artisan::output())->explode(PHP_EOL);
        $duplicate_stats = collect();
        for ($i = 4; $i < 12; $i++) {
            $duplicate_stats->push([
                'e' => intval(Str::of($duplicate_lines[$i])->explode('|')[2]),
                'el' => intval(Str::of($duplicate_lines[$i])->explode('|')[3]),
                'fl' => intval(Str::of($duplicate_lines[$i])->explode('|')[4]),
            ]);
        }

        $result = Result::make()
            ->ok()
            ->shortSummary(
                "{$duplicate_stats->max('e')} {$duplicate_stats->max('el')} {$duplicate_stats->max('fl')}"
            )
            ->meta([

            ]);

        if ($this->failWhenDuplicatesIsHigher) {
            $max_dups = max([$duplicate_stats->max('e'), $duplicate_stats->max('el'), $duplicate_stats->max('fl')]);
            if ($max_dups > $this->failWhenDuplicatesIsHigher) {
                return $result->failed("The duplicate members count is {$max_dups} which is higher than the allowed {$this->failWhenDuplicatesIsHigher}");
            }
        }

        return $result;
    }
}
