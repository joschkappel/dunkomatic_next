<?php

namespace Tests\Support;

use App\Models\User;
use App\Models\Region;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\TestCase;

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
            $this->region_user = $this->region->regionadmin->first()->user()->first();
        });
    }

    public function authenticated(Authenticatable $user = null)
    {
        return $this->actingAs($user ?? $this->region_user);
    }
}
