<?php

namespace App\Providers;

use App\Listeners\LogNotification;
use App\Listeners\SetInitialRegion;
use App\Models\League;
use App\Models\Membership;
use App\Models\User;
use App\Observers\LeagueObserver;
use App\Observers\MembershipObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Notifications\Events\NotificationSent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Authenticated::class => [
            SetInitialRegion::class,
        ],
        NotificationSent::class => [
            LogNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Membership::observe(MembershipObserver::class);
        User::observe(UserObserver::class);
        League::observe(LeagueObserver::class);
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
