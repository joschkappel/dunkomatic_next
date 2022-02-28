<?php
namespace Database\Seeders\dev;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\Role;
use App\Models\User;
use App\Models\Region;

use Silber\Bouncer\BouncerFacade as Bouncer;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $region = Region::where('code','HBVDA')->first();

        $mid = DB::table('members')->insertGetId(['lastname'=>'admin','email1'=>'admin@gmail.com']);
        $uid = DB::table('users')->insertGetId([
          'name' => 'admin',
          'email' => 'admin@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'member_id' => $mid
        ]);
        $u = User::find($uid);
        Bouncer::assign('superadmin')->to($u);
        Bouncer::allow( $u )->to('access', $region);
        Bouncer::allow( $u )->to('manage', $u);

        $mid = DB::table('members')->insertGetId(['lastname'=>'regionadmin','email1'=>'regionadmin@gmail.com']);
        $uid = DB::table('users')->insertGetId([
          'name' => 'regionadmin',
          'email' => 'regionadmin@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'member_id' => $mid
        ]);
        DB::table('memberships')->insert(['member_id'=>$mid,'role_id'=>Role::RegionLead,'membership_id'=>$region->id,'membership_type'=> Region::class ]);
        $u = User::find($uid);
        Bouncer::assign('regionadmin')->to($u);
        Bouncer::allow( $u )->to('access', $region);
        Bouncer::allow( $u )->to('manage', $u);

        $uid = DB::table('users')->insertGetId([
            'name' => 'clubadmin',
            'email' => 'clubadmin@gmail.com',
            'email_verified_at' => now(),
            'approved_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'member_id' => $mid
          ]);
          $u = User::find($uid);
          Bouncer::assign('clubadmin')->to($u);
          Bouncer::allow( $u )->to('access', $region);
          Bouncer::allow( $u )->to('manage', $u);

          $uid = DB::table('users')->insertGetId([
            'name' => 'leagueadmin',
            'email' => 'leagueadmin@gmail.com',
            'email_verified_at' => now(),
            'approved_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'member_id' => $mid
          ]);
          $u = User::find($uid);
          Bouncer::assign('leagueadmin')->to($u);
          Bouncer::allow( $u )->to('access', $region);
          Bouncer::allow( $u )->to('manage', $u);


        $uid = DB::table('users')->insertGetId([
          'name' => 'user',
          'email' => 'user@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $u = User::find($uid);
        Bouncer::assign('guest')->to($u);
        Bouncer::allow( $u )->to('access', $region);
        Bouncer::allow( $u )->to('manage', $u);

        // NO OLD USER MIGRATION !!
    }
}
