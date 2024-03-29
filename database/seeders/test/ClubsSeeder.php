<?php

namespace Database\Seeders\test;

use App\Models\Club;
use App\Models\Gym;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ClubsSeeder extends Seeder
{
    /**
     * Seed clubs
     *
     * @return void
     */
    public function run()
    {
        Club::factory()->count(40)
                       ->hasAttached(Member::factory()->count(4), new Sequence(['role_id' => 1], ['role_id' => 2], ['role_id' => 5], ['role_id' => 6]))
                       ->hasTeams(6)
                       ->has(Gym::factory()->count(3)->state(new Sequence(['gym_no' => 1], ['gym_no' => 2], ['gym_no' => 3])))
                       ->create();
    }
}
