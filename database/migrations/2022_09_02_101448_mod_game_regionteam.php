<?php

use App\Models\Game;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign('games_region_foreign');
            $table->dropColumn(['region']);
            $table->unsignedInteger('region_id_league')->nullable();
            $table->foreign('region_id_league')->references('id')->on('regions');
            $table->unsignedInteger('region_id_home')->nullable();
            $table->foreign('region_id_home')->references('id')->on('regions');
            $table->unsignedInteger('region_id_guest')->nullable();
            $table->foreign('region_id_guest')->references('id')->on('regions');
            $table->dropColumn(['team_home', 'team_guest', 'gym_no']);
        });

        // migrate data
        DB::update('update games set region_id_league = (select r.id from regions r, leagues l where l.id=league_id and r.id = l.region_id )');
        DB::update('update games set region_id_home = (select r.id from regions r, clubs c where c.id=club_id_home and r.id = c.region_id )');
        DB::update('update games set region_id_guest = (select r.id from regions r, clubs c where c.id=club_id_guest and r.id = c.region_id )');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign('games_region_id_league_foreign');
            $table->dropForeign('games_region_id_home_foreign');
            $table->dropForeign('games_region_id_guest_foreign');
            $table->dropColumn(['region_id_league', 'region_id_home', 'region_id_guest']);
            $table->string('region',5)->nullable();
            $table->foreign('region')->references('code')->on('regions');
            $table->string('team_home',5)->nullable();
            $table->string('team_guest',5)->nullable();
            $table->string('gym_no',2)->nullable();
        });
        // migrate data
        DB::update('update games set region = (select r.code from regions r, leagues l where l.id=league_id and r.id = l.region_id )');
        DB::update('update games set team_home = (select concat(c.shortname, t.team_no) from clubs c, teams t where c.id=club_id_home and t.id = team_id_home )');
        DB::update('update games set team_guest = (select concat(c.shortname, t.team_no) from clubs c, teams t where c.id=club_id_guest and t.id = team_id_guest )');
        DB::update('update games set gym_no = (select g.gym_no from gyms g where g.id=gym_id )');
    }
};
