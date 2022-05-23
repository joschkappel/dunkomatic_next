<?php
namespace Database\Seeders\base;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        ['name' => 'season', 'value' => '2021/22'],
        ['name' => 'global_alert', 'value' => null],
      ]);
    }
}
