<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

use App\Models\League;
use App\Models\Member;
use App\Models\Club;
use App\Models\Team;

class TestLeaguesSeeder extends Seeder
{
    /**
     * Seed clubs
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 20; $i++){
            League::factory()->hasAttached( Member::factory(), ['role_id'=>3])
                            ->hasAttached( Club::all()->random(4), new Sequence (['league_char'=>'A', 'league_no'=>1],
                                                                                ['league_char'=>'B', 'league_no'=>2],
                                                                                ['league_char'=>'C', 'league_no'=>3],
                                                                                ['league_char'=>'D', 'league_no'=>4]))
                            ->create();
        }
    }
}
