<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('roles')->insert([
        ['id' => '1', 'name' => 'Abteilungsleiter', 'shortname' => 'Abt-Ltr.', 'scope' => 'CLUB','created_at' => now()],
        ['id' => '2', 'name' => 'Schiedsrichterwart', 'shortname' => 'SR-Wart', 'scope' => 'CLUB','created_at' => now()],
        ['id' => '3', 'name' => 'Staffelleiter', 'shortname' => 'Stf-Ltr.', 'scope' => 'LEAGUE','created_at' => now()],
        ['id' => '4', 'name' => 'Bezirksmitarbeiter', 'shortname' => 'Bezirk', 'scope' => 'REGION','created_at' => now()],
        ['id' => '5', 'name' => 'Verantw. Mädchenbasket', 'shortname' => 'Mäd.bkt.', 'scope' => 'CLUB','created_at' => now()],
        ['id' => '6', 'name' => 'Jugendwart', 'shortname' => 'JGD-Wart', 'scope' => 'CLUB','created_at' => now()]
      ]);        //
    }
}
