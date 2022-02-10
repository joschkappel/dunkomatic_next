<?php

namespace Tests\Support;

use App\Models\User;
use App\Models\Region;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\TestCase;

use Silber\Bouncer\BouncerFacade as Bouncer;


trait Authentication
{
    /** @var User $region_user **/
    protected $region_user;
    /** @var Region $region **/
    protected $region;

    /**
     * @before
     */
    public function setupUser()
    {
        $this->afterApplicationCreated(function () {
            $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
            $this->region = Region::where('code','HBVDA')->first();
            $this->region_user = $this->region->regionadmin()->first()->user()->first();

            Bouncer::sync($this->region_user)->roles([]);
            Bouncer::assign( 'superadmin')->to($this->region_user);
            Bouncer::allow($this->region_user)->to('access',$this->region);
            Bouncer::refreshFor($this->region_user);
        });
    }

    public function authenticated(Authenticatable $user = null)
    {
        return $this->actingAs($user ?? $this->region_user);
    }
}
