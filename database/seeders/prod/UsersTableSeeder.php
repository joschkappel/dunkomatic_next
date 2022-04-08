<?php

namespace Database\Seeders\prod;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Region;
use App\Enums\Role;
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

        // createa superadmin

        $u =  User::create([
            'name' => 'admin',
            'email' => 'joschkappel@gmail.com',
            'email_verified_at' => now(),
            'approved_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
        Bouncer::assign('superadmin')->to($u);
        Bouncer::allow($u)->to('manage', $u);

        // create 5 regionadmins

        // HBVDA
        $r = Region::where('code', 'HBVDA')->first();
        $u = User::create([
            'name' => 'Detlef Volk',
            'email' => 'bzvs@dunkomatic.de',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'locale' => 'de',
        ]);
        $this->approveAndSetAcls($u, $r);

        // HBV
        $r = Region::where('code', 'HBV')->first();
        $u = User::create([
            'name' => 'Karin Arndt',
            'email' => 'arndtkarin@t-online.de',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'locale' => 'de',
        ]);
        $this->approveAndSetAcls($u, $r);

        // HBVF
        $r = Region::where('code', 'HBVF')->first();
        $u = User::create([
            'name' => 'Günter Herzog',
            'email' => 'guenter_herzog@gmx.de',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'locale' => 'de',
        ]);
        $this->approveAndSetAcls($u, $r);

        // HBVKS
        $r = Region::where('code', 'HBVKS')->first();
        $u = User::create([
            'name' => 'Markus Hegler',
            'email' => 'kasselbasketball@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'locale' => 'de',
        ]);
        $this->approveAndSetAcls($u, $r);

        // HBVGI
        $r = Region::where('code', 'HBVGI')->first();
        $u = User::create([
            'name' => 'Petra Cramer',
            'email' => 'petra.cramer@gmx.de',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'locale' => 'de',
        ]);
        $this->approveAndSetAcls($u, $r);


    }


    public function approveAndSetAcls(User $u, Region $r){
        // approve and kick off observer to assigne mebmer
        $u->update(['approved_at' => now()]);

        // create a RegionLead membership
        $r->memberships()->create([
            'role_id' => Role::RegionLead,
            'member_id' => $u->member->id,
        ]);

        Bouncer::assign('regionadmin')->to($u);
        Bouncer::allow($u)->to('manage', $u);
        Bouncer::allow($u)->to('access', $r);
    }
}
