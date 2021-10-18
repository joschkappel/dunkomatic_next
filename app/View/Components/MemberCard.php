<?php

namespace App\View\Components;

use App\Models\Club;
use App\Models\League;
use App\Models\Region;

use Illuminate\View\Component;

class MemberCard extends Component
{

    public $members;
    public $entityClass;
    public $entityType;
    public $entity;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($members, $entity, $entityClass )
    {
        $this->members = $members;
        $this->entity = $entity;
        $this->entityClass = $entityClass;

        if ($entityClass == Club::class ){
            $this->entityType = 'club';
        };

        if ($entityClass == League::class ){
            $this->entityType = 'league';
        } ;
        if ($entityClass == Region::class ){
            $this->entityType = 'region';
        };

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        // <x-member-card :members="$members" :entity="$club" entity-class="App\Models\Club" />

        return view('components.member-card');
    }
}
