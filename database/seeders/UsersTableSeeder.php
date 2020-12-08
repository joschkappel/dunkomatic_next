<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\Role;
use App\Models\User;
use App\Models\Member;
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

        Bouncer::allow('superadmin')->everything();
        Bouncer::assign('superadmin')->to(User::find($uid));

        $mid = DB::table('members')->insertGetId(['lastname'=>'region','email1'=>'region@gmail.com']);
        $uid = DB::table('users')->insertGetId([
          'name' => 'region',
          'user_old' => 'admin',
          'email' => 'region@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'region_id' => Region::where('code','HBVDA')->first()->id,
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'member_id' => $mid
        ]);
        DB::table('memberships')->insert(['member_id'=>$mid,'role_id'=>Role::RegionLead,'membershipable_id'=>Region::where('code','HBVDA')->first()->id,'membershipable_type'=> Region::class ]);

        Bouncer::allow('admin')->to('edit-region');
        Bouncer::assign('admin')->to(User::find($uid));


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

        DB::table('memberships')->insert(['member_id'=>$mid,'role_id'=>Role::User,'membershipable_id'=>25,'membershipable_type'=>'App\Models\Club' ]);
        DB::table('memberships')->insert(['member_id'=>$mid,'role_id'=>Role::User,'membershipable_id'=>26,'membershipable_type'=>'App\Models\Club' ]);



        // NO OLD USER MIGRATION !!
    }
}
