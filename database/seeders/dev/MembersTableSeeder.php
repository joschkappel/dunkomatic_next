<?php
namespace Database\Seeders\dev;

use App\Models\Club;
use App\Models\League;
use App\Models\Member;
use App\Models\Membership;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class MembersTableSeeder extends Seeder
{
    protected $faker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $this->faker = Factory::create('de_DE');

      $old_row = DB::connection('dunkv1')->table('member')->get();

      foreach ($old_row as $row) {

        // check if member with that email already exists
        if ( Member::on('dunknxt')->where('firstname', $row->firstname )->where('lastname', $row->lastname )->exists()){
          $member = Member::on('dunknxt')->where('firstname', $row->firstname )->where('lastname', $row->lastname )->first();
          $mem_id = $member->id;
        } else {
          $mem_id = DB::connection('dunknxt')->table('members')->insertGetId([
            'lastname' => $this->faker->lastname,
            'email1' => $this->faker->unique()->safeEmail,
            'firstname' => $this->faker->firstname,
            'zipcode' => $this->faker->postcode,
            'city' => $this->faker->city,
            'street' => $this->faker->streetAddress,
            'mobile' => $this->faker->phoneNumber,
            'phone'    => $this->faker->phoneNumber,
            'email2'   => $this->faker->unique()->safeEmail,
            'fax'      => $this->faker->phoneNumber,
            'created_at'    => now()
          ]);
        }

        if (($row->club_id != 0) and ($row->member_role_id != 2)) {
          if ( ! Membership::on('dunknxt')->where('membership_type', Club::class)
                                          ->where('membership_id', $row->club_id)
                                          ->where('member_id', $mem_id)
                                          ->where('role_id', $row->member_role_id +1)
                                          ->exists()){
            DB::connection('dunknxt')->table('memberships')->insert([
              'membership_id'     => $row->club_id,
              'membership_type'   => Club::class,
              'member_id'     => $mem_id,
              'role_id'       => $row->member_role_id +1,
              'email'         => $this->faker->unique()->safeEmail,
              'created_at'    => now()
            ]);
          }

        };

        if (($row->league_id != 0) and ($row->member_role_id == 2)) {
          if ( ! Membership::on('dunknxt')->where('membership_type', League::class)
                                          ->where('membership_id', $row->league_id)
                                          ->where('member_id', $mem_id)
                                          ->where('role_id', $row->member_role_id +1)
                                          ->exists()){
            DB::connection('dunknxt')->table('memberships')->insert([
              'membership_id'     => $row->league_id,
              'membership_type'   => League::class,
              'member_id'     => $mem_id,
              'role_id'       => $row->member_role_id +1,
              'function'      => $row->function,
              'email'         => $this->faker->unique()->safeEmail,
              'created_at'    => now()
            ]);
          }
        };

      }
    }
}
