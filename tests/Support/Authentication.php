<?php

namespace Tests\Support;

use App\Models\Region;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Silber\Bouncer\BouncerFacade as Bouncer;

trait Authentication
{
    /** @var User * */
    protected $region_user;

    /** @var Region * */
    protected $region;

    /**
     * @before
     */
    public function setupUser()
    {
        $this->afterApplicationCreated(function () {
            $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
            $this->region = Region::where('code', 'HBVDA')->first();
            $this->region_user = $this->region->regionadmins()->first()->user()->first();

            Bouncer::sync($this->region_user)->roles([]);
            Bouncer::assign('superadmin')->to($this->region_user);
            Bouncer::assign('regionadmin')->to($this->region_user);
            Bouncer::allow($this->region_user)->to('access', $this->region);
            Bouncer::allow($this->region_user)->to('manage', $this->region_user);
            Bouncer::refreshFor($this->region_user);
        });
    }

    public function authenticated(Authenticatable $user = null)
    {
        return $this->actingAs($user ?? $this->region_user);
    }
}
