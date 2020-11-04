<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\Role;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $uid = DB::table('users')->insertGetId([
          'name' => 'approved',
          'user_old' => 'approved',
          'email' => 'approved@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'region' => 'HBVDA',
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'admin' => false,
          'regionadmin' => false
        ]);
        DB::table('members')->insert(['lastname'=>'approved','email1'=>'approved@gmail.com','user_id'=>$uid]);

        $uid = DB::table('users')->insertGetId([
          'name' => 'notapproved',
          'user_old' => 'notapproved',
          'email' => 'notapproved@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => null,
          'region' => 'HBVDA',
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'admin' => false,
          'regionadmin' => false
        ]);
        DB::table('members')->insert(['lastname'=>'notapproved','email1'=>'notapproved@gmail.com','user_id'=>$uid]);
    }
}
