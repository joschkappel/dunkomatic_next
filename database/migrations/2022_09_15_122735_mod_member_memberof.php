<?php

use App\Models\Membership;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('member_of_clubs')->nullable();
            $table->string('member_of_leagues')->nullable();
            $table->string('member_of_teams')->nullable();
            $table->string('member_of_regions')->nullable();
            $table->text('role_in_clubs')->nullable();
            $table->text('role_in_leagues')->nullable();
            $table->text('role_in_teams')->nullable();
            $table->text('role_in_regions')->nullable();
        });

        foreach( Membership::all()  as $ms){
            $ms->touch();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['member_of_clubs','member_of_leagues','member_of_regions','member_of_teams']);
            $table->dropColumn(['role_in_clubs','role_in_leagues','role_in_regions','role_in_teams']);
        });
    }
};
