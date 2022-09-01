<?php

namespace Database\Seeders;

use App\Enums\Report;
use App\Models\Game;
use App\Models\Club;
use App\Models\Gym;
use App\Models\Team;
use App\Models\League;
use App\Models\Member;
use App\Models\Membership;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ReportClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('report_classes')->insert([
            ['report_id' => Report::AddressBook, 'report_class' => Club::class],
            ['report_id' => Report::AddressBook, 'report_class' => Gym::class],
            ['report_id' => Report::AddressBook, 'report_class' => League::class],
            ['report_id' => Report::AddressBook, 'report_class' => Member::class],
            ['report_id' => Report::AddressBook, 'report_class' => Membership::class],
            ['report_id' => Report::AddressBook, 'report_class' => Team::class],

            ['report_id' => Report::LeagueBook, 'report_class' => Game::class],
            ['report_id' => Report::LeagueBook, 'report_class' => League::class],
            ['report_id' => Report::LeagueBook, 'report_class' => Team::class],
            ['report_id' => Report::LeagueBook, 'report_class' => Gym::class],

            ['report_id' => Report::Teamware, 'report_class' => Game::class],
            ['report_id' => Report::Teamware, 'report_class' => Team::class],

            ['report_id' => Report::RegionGames, 'report_class' => Game::class],
            ['report_id' => Report::RegionGames, 'report_class' => League::class],
            ['report_id' => Report::RegionGames, 'report_class' => Team::class],
            ['report_id' => Report::RegionGames, 'report_class' => Gym::class],


            ['report_id' => Report::ClubGames, 'report_class' => Game::class],
            ['report_id' => Report::ClubGames, 'report_class' => League::class],
            ['report_id' => Report::ClubGames, 'report_class' => Team::class],
            ['report_id' => Report::ClubGames, 'report_class' => Gym::class],

            ['report_id' => Report::LeagueGames, 'report_class' => Game::class],
            ['report_id' => Report::LeagueGames, 'report_class' => League::class],
            ['report_id' => Report::LeagueGames, 'report_class' => Team::class],
            ['report_id' => Report::LeagueGames, 'report_class' => Gym::class],

        ]);
    }
}
