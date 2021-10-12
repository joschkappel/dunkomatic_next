<?php
namespace Database\Seeders\dev;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\Role;
use App\Models\User;
use App\Models\Region;

use Bouncer;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mid = DB::table('members')->insertGetId(['lastname'=>'admin','email1'=>'admin@gmail.com']);
        $uid = DB::table('users')->insertGetId([
          'name' => 'admin',
          'user_old' => 'admin',
          'email' => 'admin@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'region_id' => Region::where('code','HBV')->first()->id,
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'member_id' => $mid
        ]);
        Bouncer::assign('superadmin')->to(User::find($uid));

        $mid = DB::table('members')->insertGetId(['lastname'=>'regionadmin','email1'=>'regionadmin@gmail.com']);
        $uid = DB::table('users')->insertGetId([
          'name' => 'regionadmin',
          'user_old' => 'admin',
          'email' => 'regionadmin@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'region_id' => Region::where('code','HBVDA')->first()->id,
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'member_id' => $mid
        ]);
        DB::table('memberships')->insert(['member_id'=>$mid,'role_id'=>Role::RegionLead,'membership_id'=>Region::where('code','HBVDA')->first()->id,'membership_type'=> Region::class ]);
        Bouncer::assign('regionadmin')->to(User::find($uid));

        $mid = DB::table('members')->insertGetId(['lastname'=>'regionassist','email1'=>'regionassist@gmail.com']);
        $uid = DB::table('users')->insertGetId([
          'name' => 'regionassist',
          'user_old' => 'assist',
          'email' => 'regionassist@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'region_id' => Region::where('code','HBVDA')->first()->id,
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'member_id' => $mid
        ]);
        DB::table('memberships')->insert(['member_id'=>$mid,'role_id'=>Role::RegionLead,'membership_id'=>Region::where('code','HBVDA')->first()->id,'membership_type'=> Region::class ]);
        Bouncer::assign('regionassist')->to(User::find($uid));

        $uid = DB::table('users')->insertGetId([
            'name' => 'clubadmin',
            'user_old' => 'assist',
            'email' => 'clubadmin@gmail.com',
            'email_verified_at' => now(),
            'approved_at' => now(),
            'region_id' => Region::where('code','HBVDA')->first()->id,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'member_id' => $mid
          ]);
          Bouncer::assign('clubadmin')->to(User::find($uid));

          $uid = DB::table('users')->insertGetId([
            'name' => 'clubassist',
            'user_old' => 'assist',
            'email' => 'clubassist@gmail.com',
            'email_verified_at' => now(),
            'approved_at' => now(),
            'region_id' => Region::where('code','HBVDA')->first()->id,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'member_id' => $mid
          ]);
          Bouncer::assign('clubassist')->to(User::find($uid));

          $uid = DB::table('users')->insertGetId([
            'name' => 'leagueadmin',
            'user_old' => 'assist',
            'email' => 'leagueadmin@gmail.com',
            'email_verified_at' => now(),
            'approved_at' => now(),
            'region_id' => Region::where('code','HBVDA')->first()->id,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'member_id' => $mid
          ]);
          Bouncer::assign('leagueadmin')->to(User::find($uid));


        $mid = DB::table('members')->insertGetId(['lastname'=>'user','email1'=>'user@gmail.com']);
        $uid = DB::table('users')->insertGetId([
          'name' => 'user',
          'user_old' => 'admin',
          'email' => 'user@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'region_id' => Region::where('code','HBVDA')->first()->id,
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'member_id' => $mid
        ]);

        DB::table('memberships')->insert(['member_id'=>$mid,'role_id'=>Role::User,'membership_id'=>25,'membership_type'=>'App\Models\Club' ]);
        DB::table('memberships')->insert(['member_id'=>$mid,'role_id'=>Role::User,'membership_id'=>26,'membership_type'=>'App\Models\Club' ]);

        Bouncer::assign('guest')->to(User::find($uid));

        // NO OLD USER MIGRATION !!
    }
}
