<?php

namespace Database\Seeders\prod;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Region;
use App\Enums\Role;

// use Illuminate\Support\Facades\Hash;   Hash::make('HBVXX-2022', ['rounds'=>12])
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
            'password' => '$2y$12$HOF0RNK6nYIu/tV12sUg5eMOFjFqSRwkf.6gUJadUX.VtFOBbBMRK'
        ]);
        Bouncer::assign('superadmin')->to($u);
        Bouncer::allow($u)->to('manage', $u);

        // create 5 regionadmins

        // HBVDA
        $r = Region::where('code', 'HBVDA')->first();
        $u = User::create([
            'name' => 'Detlef Volk',
            'email' => 'detlef.volk@icloud.com',
            'email_verified_at' => now(),
            'password' => '$2y$12$0sJK6yb8XjofKRjmUPWwLu90.z.nwI4eJ2YoEFJu6xn2n4mVZdDHO',
            'locale' => 'de',
        ]);
        $this->approveAndSetAcls($u, $r);

        // HBV
        $r = Region::where('code', 'HBV')->first();
        $u = User::create([
            'name' => 'Karin Arndt',
            'email' => 'arndtkarin@t-online.de',
            'email_verified_at' => now(),
            'password' => '$2y$12$kKkm8t3teKQeeQGOxF0uDuZ8O4e5RquKbuLIg39n3gatZgYiKCQjq',
            'locale' => 'de',
        ]);
        $this->approveAndSetAcls($u, $r);

        // HBVF
        $r = Region::where('code', 'HBVF')->first();
        $u = User::create([
            'name' => 'Günter Herzog',
            'email' => 'guenter_herzog@gmx.de',
            'email_verified_at' => now(),
            'password' => '$2y$12$6Ig7Hqtb2YBbZxHHHN4oBO37h4nWS0wi9DBe27k/xwRRZ7M12h0Rq',
            'locale' => 'de',
        ]);
        $this->approveAndSetAcls($u, $r);

        // HBVKS
        $r = Region::where('code', 'HBVKS')->first();
        $u = User::create([
            'name' => 'Markus Hegler',
            'email' => 'kasselbasketball@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$12$Fh42iC6znKVHyvgekH5ZluBWN3RCENp1mmkFHXxQyxcEGmczrGpLm',
            'locale' => 'de',
        ]);
        $this->approveAndSetAcls($u, $r);

        // HBVGI
        $r = Region::where('code', 'HBVGI')->first();
        $u = User::create([
            'name' => 'Petra Cramer',
            'email' => 'petra.cramer@gmx.de',
            'email_verified_at' => now(),
            'password' => '$2y$12$9FTuAquqJ.Sx7pPPkXOTmuHCLZe7Z6xRq/ukhb/zuNqSOAnm.oBA6',
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
