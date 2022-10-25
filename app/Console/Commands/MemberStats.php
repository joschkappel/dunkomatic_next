<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MemberStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmatic:memberstats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show statistics on duplicate members';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $mtable = [];

        for ($i = 2; $i < 10; $i++) {
            $mtable[] = [$i, Member::whereIn('email1', function ($query) use ($i) {
                $query->selectRaw('email1 from members
                                    WHERE email1 is not null
                                    AND email1!=""
                                    GROUP BY email1
                                    HAVING count(email1) = '.$i);
            })->orderBy('email1')->get()->chunk($i)->count(),
                Member::whereIn(DB::raw('concat(email1, lastname)'), function ($query) use ($i) {
                    $query->selectRaw('concat(email1, lastname) as name
                                   FROM members
                                   WHERE email1 is not null
                                   AND email1!=""
                                   GROUP BY name
                                   HAVING count(concat(email1, lastname)) = '.$i);
                })->orderBy('lastname')->get()->chunk($i)->count(),
                Member::whereIn(DB::raw('concat(firstname, lastname)'), function ($query) use ($i) {
                    $query->selectRaw('concat(firstname, lastname) as name
                                    FROM members
                                    WHERE email1 is not null
                                    AND email1!=""
                                    GROUP BY name
                                    HAVING count(concat(firstname, lastname)) = '.$i);
                })->orderBy('lastname')->get()->chunk($i)->count(),
            ];
        }

        $this->newline();
        $this->table(
            ['# of duplicates', 'by email1', 'by email1 and lastname', 'by firstname and lastname'],
            $mtable
        );

        return 0;
    }
}
