<?php

namespace Database\Seeders\fixes;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamGym extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Team::all() as $t) {
            $t->gym()->associate($t->club->gyms()->first());
            $t->save();
        }
    }
}
