<?php

namespace App\Events;

use App\Menu\Builder;
use Illuminate\Support\Facades\Auth;

class BuildingMenu
{
    /**
     * The menu builder.
     *
     * @var Builder
     */
    public $menu;

    /**
     * Create a new event instance.
     *
     * @param Builder $menu
     */
    public function __construct(Builder $menu)
    {
        if ( Auth::check() ){
          $this->menu = $menu;
        }
    }
}
