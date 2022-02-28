<?php
namespace Database\Seeders\test;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Region;
use App\Models\User;

use Silber\Bouncer\BouncerFacade as Bouncer;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $region = Region::where('code','HBVDA')->first();
        $mid = DB::table('members')->insertGetId(['lastname'=>'approved','email1'=>'approved@gmail.com']);
        $uid = DB::table('users')->insertGetId([
          'name' => 'approved',
          'email' => 'approved@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'member_id' => $mid
        ]);
        $u = User::find($uid);
        Bouncer::assign('regionadmin')->to($u);
        Bouncer::allow( $u )->to('access', $region);
        Bouncer::allow( $u )->to('manage', $u);


        $mid = DB::table('members')->insertGetId(['lastname'=>'notapproved','email1'=>'notapproved@gmail.com']);
        $uid = DB::table('users')->insertGetId([
          'name' => 'notapproved',
          'email' => 'notapproved@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => null,
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'member_id' => $mid
        ]);
        Bouncer::assign('candidate')->to(User::find($uid));
        Bouncer::allow(User::find($uid))->to('access', $region);

    }
}
