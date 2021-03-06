<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\Role;
use App\Models\Region;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $uid = DB::table('members')->insertGetId(['lastname'=>'approved','email1'=>'approved@gmail.com']);
        DB::table('users')->insert([
          'name' => 'approved',
          'user_old' => 'approved',
          'email' => 'approved@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'region_id' => Region::where('code','HBVDA')->first()->id,
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'member_id' => $uid
        ]);


        $uid = DB::table('members')->insertGetId(['lastname'=>'notapproved','email1'=>'notapproved@gmail.com']);
        DB::table('users')->insert([
          'name' => 'notapproved',
          'user_old' => 'notapproved',
          'email' => 'notapproved@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => null,
          'region_id' => Region::where('code','HBVDA')->first()->id,
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'member_id' => $uid
        ]);

    }
}
