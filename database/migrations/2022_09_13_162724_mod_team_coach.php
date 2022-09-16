<?php

use App\Models\Team;
use App\Models\Member;
use App\Enums\Role;
use App\Models\Membership;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // migrate data -move from teams to member
        foreach (Team::all() as $t){
            // try to avoid duplicates
            // try to split name in fiorstname and lastname

            $name = Str::of($t->coach_name)->squish()->explode(' ');
            $mname = collect();
            if ( $name->count() == 1 ){
                // just "NN"
                $mname->push(['f'=>'', 'l'=>$name]);
            } elseif ( $name->count() == 2 ){
                // normal "Otto Müller"
                $mname->push(['f'=>$name[0], 'l'=>$name[1]]);
            } elseif ( $name->count() == 3 ){
                // either "Otto Karl Müller" or "Maria Müller,Otto Müller"
                $middlename = Str::of($name[1])->explode(',');
                if ( $middlename->count() > 1){
                    $mname->push(['f'=>$name[0], 'l'=>$middlename[0]]);
                    $mname->push(['f'=>$middlename[1], 'l'=>$name[2]]);
                } else {
                    $mname->push(['f'=>$name[0].' '.$name[1], 'l'=>$name[2]]);
                }
            } elseif ( $name->count() == 4 ){
                // "Maria Müller, Otto Müller"
                $mname->push(['f'=>$name[0], 'l'=>Str::replace(',','',$name[1]) ]);
                $mname->push(['f'=>$name[2], 'l'=>$name[3]]);
            } elseif ( $name->count() == 5 ){
                // "Maria Müller and Otto Müller"
                $mname->push(['f'=>$name[0], 'l'=>$name[1]]);
                $mname->push(['f'=>$name[3], 'l'=>$name[4]]);
            }
            Log::info('migrate coaches. working on ',['team'=>$t->name,'coaches'=>$mname]);

            foreach ( $mname as $mn){
                $m = Member::where('lastname',$mn['l'])->where('email1',$t->coach_email )->get();
                if ( $m->count() > 0 ){
                    $m = $m->first();
                    Log::notice('migrate coaches. member exists ',['team'=>$t->name,'coaches'=>$mn,'member'=>$m->name,'member-id'=>$m->id]);
                } else {
                    $m = Member::create([
                        'firstname' => $mn['f'],
                        'lastname' => $mn['l'],
                        'email1' => $t->coach_email ?? 'missing',
                        'mobile' => $t->coach_phone1,
                        'phone' => $t->coach_phone2
                    ]);
                    Log::notice('migrate coaches. member created ',['team'=>$t->name,'coaches'=>$mn,'member'=>$m->name,'member-id'=>$m->id]);
                }
                $mship = [];
                $mship['member_id'] = $m->id;
                $mship['role_id'] = Role::TeamCoach();

                $ms = Membership::withoutEvents(function () use ($t, $mship){
                    $ms = $t->memberships()->create($mship);
                    return $ms;
                });
                Log::notice('migrate coaches. membership attached',['team'=>$t->name,'member-id'=>$ms->member_id,'team-id'=>$ms->membership_id,'mship-id'=>$m->id]);

            }
        }

        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['coach_name', 'coach_phone1', 'coach_phone2', 'coach_email']);
            $table->dropColumn('changeable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('coach_name')->nullable();
            $table->string('coach_phone1')->nullable();
            $table->string('coach_phone2')->nullable();
            $table->string('coach_email')->nullable();
            $table->boolean('changeable')->default(True);
        });

        foreach( Membership::where('membership_type', Team::class)->get() as $ms ){
            $t = Team::find($ms->membership_id);
            $t->update([
                'coach_name' => $ms->member->firstname.' '.$ms->member->lastname,
                'coach_email' => $ms->member->email1,
                'coach_phone1' => $ms->member->mobile,
                'coach_phone2' => $ms->member->phone
            ]);

            $ms->member->delete();
            $ms->delete();
        }
    }
};
