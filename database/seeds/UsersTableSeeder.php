<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
          'name' => 'admin',
          'user_old' => 'admin',
          'email' => 'admin@gmail.com',
          'email_verified_at' => now(),
          'region' => 'HBV',
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'superuser' => true,
          'regionuser' => true
        ]);
        DB::table('users')->insert([
          'name' => 'region',
          'user_old' => 'admin',
          'email' => 'region@gmail.com',
          'email_verified_at' => now(),
          'region' => 'HBVDA',
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'superuser' => false,
          'regionuser' => true
        ]);
        DB::table('users')->insert([
          'name' => 'user',
          'user_old' => 'admin',
          'email' => 'user@gmail.com',
          'email_verified_at' => now(),
          'region' => 'HBVDA',
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'superuser' => false,
          'regionuser' => false,
          'club_ids' => "25,26",
        ]);

        {

          // only active users
          $old_user = DB::connection('dunkv1')->table('system_manager')->distinct()->where("active", "1")->get();


          foreach ($old_user as $user) {

            $superuser = false;
            $regionuser = false;

            if ( $user->security_group_id == 4 ){
              $superuser = true;
            }
            if ( $user->security_group_id == 5 ){
              $regionuser = true;
            }

            if (DB::connection('dunknxt')->table('users')->where('email',$user->email)->doesntExist()){

              DB::connection('dunknxt')->table('users')->insert([
                'name'          => $user->system_manager_name,
                'user_old'      => $user->username,
                'email'         => $user->email,
                'email_verified_at'      => now(),
                'region'        => 'HBV',
                'password'     => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'superuser'     => $superuser,
                'regionuser'    => $regionuser,
                'created_at'    => now()
              ]);
            }
          }
        }
    }
}
