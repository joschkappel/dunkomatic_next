<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('settings')->insert([
        ['name' => 'season', 'value' => '2020/21'],
        ['name' => 'global_alert', 'value' => null],
      ]);
    }
}
