<?php

use Illuminate\Database\Seeder;

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
        $mem_id = DB::connection('dunknxt')->table('members')->insertGetId([
          'firstname'          => $row->firstname,
          'lastname'          => $row->lastname,
          'city'             => $row->city,
          'zipcode'          => $row->zip,
          'street'          => $row->street,
          'phone1'          => $row->phone1,
          'phone2'          => $row->phone2,
          'mobile'          => $row->mobile,
          'email1'          => $row->email,
          'email2'          => $row->email2,
          'fax1'          => $row->fax1,
          'fax2'          => $row->fax2,
          'created_at'    => now()
        ]);

        if ($row->club_id != 0){
          DB::connection('dunknxt')->table('member_roles')->insert([
            'unit_id'     => $row->club_id,
            'unit_type'   => 'App\Club',
            'member_id'     => $mem_id,
            'role_id'       => $row->member_role_id +1,
            'created_at'    => now()
          ]);

        };

        if (($row->league_id != 0) and ($row->member_role_id == 2)) {
          DB::connection('dunknxt')->table('member_roles')->insert([
            'unit_id'     => $row->league_id,
            'unit_type'   => 'App\League',
            'member_id'     => $mem_id,
            'role_id'       => $row->member_role_id +1,
            'function'      => $row->function,
            'created_at'    => now()
          ]);
        };

      }
    }
}
