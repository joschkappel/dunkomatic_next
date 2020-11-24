<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\Role;
use App\Models\User;
use App\Models\Member;

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
        $uid = DB::table('users')->insertGetId([
          'name' => 'admin',
          'user_old' => 'admin',
          'email' => 'admin@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'region' => 'HBV',
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'admin' => true,
          'regionadmin' => false
        ]);
        DB::table('members')->insert(['lastname'=>'admin','email1'=>'admin@gmail.com','user_id'=>$uid]);

        Bouncer::allow('superadmin')->everything();
        Bouncer::assign('superadmin')->to(User::find($uid));


        $uid = DB::table('users')->insertGetId([
          'name' => 'region',
          'user_old' => 'admin',
          'email' => 'region@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'region' => 'HBVDA',
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'admin' => false,
          'regionadmin' => true
        ]);
        DB::table('members')->insert(['lastname'=>'region','email1'=>'region@gmail.com','user_id'=>$uid]);
        Bouncer::allow('admin')->to('edit-region');
        Bouncer::assign('admin')->to(User::find($uid));        

        $uid = DB::table('users')->insertGetId([
          'name' => 'user',
          'user_old' => 'admin',
          'email' => 'user@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'region' => 'HBVDA',
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'admin' => false,
          'regionadmin' => false,
        ]);
        DB::table('members')->insert(['lastname'=>'user','email1'=>'user@gmail.com','user_id'=>$uid]);
        DB::table('memberships')->insert(['member_id'=>$uid,'role_id'=>Role::User,'membershipable_id'=>25,'membershipable_type'=>'App\Models\Club' ]);
        DB::table('memberships')->insert(['member_id'=>$uid,'role_id'=>Role::User,'membershipable_id'=>26,'membershipable_type'=>'App\Models\Club' ]);



        // NO OLD USER MIGRATION !!
        // {
        //
        //   // only active users
        //   $old_user = DB::connection('dunkv1')->table('system_manager')->distinct()->where("active", "1")->get();
        //
        //
        //   foreach ($old_user as $user) {
        //
        //     $superuser = false;
        //     $regionadmin = false;
        //
        //     if ( $user->security_group_id == 4 ) {
        //       $superuser = true;
        //     }
        //     if ( $user->security_group_id == 5 ) {
        //       $regionadmin = true;
        //     }
        //
        //     if (DB::connection('dunknxt')->table('users')->where('email',$user->email)->doesntExist()){
        //
        //       DB::connection('dunknxt')->table('users')->insert([
        //         'name'          => $user->system_manager_name,
        //         'user_old'      => $user->username,
        //         'email'         => $user->email,
        //         'email_verified_at' => now(),
        //         'approved_at' => null,
        //         'region'        => 'HBV',
        //         'password'     => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //         'admin'     => $superuser,
        //         'regionadmin' => $regionadmin,
        //         'created_at'    => now()
        //       ]);
        //     }
        //   }
        // }
    }
}
