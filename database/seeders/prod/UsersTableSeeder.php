<?php
namespace Database\Seeders\prod;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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

        $uid = DB::table('users')->insertGetId([
          'name' => 'admin',
          'user_old' => 'admin',
          'email' => 'joschkappel@gmail.com',
          'email_verified_at' => now(),
          'approved_at' => now(),
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
        Bouncer::assign('superadmin')->to(User::find($uid));


    }
}
