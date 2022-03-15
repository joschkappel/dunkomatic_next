<?php
namespace Database\Seeders\prod;

use App\Models\Club;
use App\Models\League;
use App\Models\Member;
use App\Models\Membership;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $old_row = DB::connection('dunkv1')->table('member')->get();

      foreach ($old_row as $row) {

        // check if member with that email already exists
        if ( Member::on('dunknxt')->where('firstname', $row->firstname )->where('lastname', $row->lastname )->exists()){
          $member = Member::on('dunknxt')->where('firstname', $row->firstname )->where('lastname', $row->lastname )->first();
          $mem_id = $member->id;
        } else {
          $mem_id = DB::connection('dunknxt')->table('members')->insertGetId([
            'firstname'          => $row->firstname,
            'lastname'          => $row->lastname,
            'city'             => $row->city,
            'zipcode'          => $row->zip,
            'street'          => $row->street,
            'phone'          => $row->phone1,
            'mobile'          => $row->mobile,
            'email1'          => $row->email,
            'email2'          => $row->email2,
            'fax'          => $row->fax1,
            'created_at'    => now()
          ]);
        }

        if (($row->club_id != 0) and ($row->member_role_id != 2)) {
          if ( ! Membership::on('dunknxt')->where('membership_type', Club::class)
                                          ->where('membership_id', $row->club_id)
                                          ->where('member_id', $mem_id)
                                          ->where('role_id', $row->member_role_id)
                                          ->exists()){
            DB::connection('dunknxt')->table('memberships')->insert([
              'membership_id'     => $row->club_id,
              'membership_type'   => Club::class,
              'member_id'     => $mem_id,
              'role_id'       => $row->member_role_id,
              'email'         => $row->email,
              'created_at'    => now()
            ]);
          }

        };

        if (($row->league_id != 0) and ($row->member_role_id == 2)) {
          if ( ! Membership::on('dunknxt')->where('membership_type', League::class)
                                          ->where('membership_id', $row->league_id)
                                          ->where('member_id', $mem_id)
                                          ->where('role_id', $row->member_role_id )
                                          ->exists()){
            DB::connection('dunknxt')->table('memberships')->insert([
              'membership_id'     => $row->league_id,
              'membership_type'   => League::class,
              'member_id'     => $mem_id,
              'role_id'       => $row->member_role_id,
              'function'      => $row->function,
              'email'         => $row->email,
              'created_at'    => now()
            ]);
          }
        };

      }
    }
}
