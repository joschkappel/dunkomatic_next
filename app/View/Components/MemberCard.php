<?php

namespace App\View\Components;

use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use Illuminate\View\Component;

class MemberCard extends Component
{

    public Collection $members;
    public string $entityClass;
    public string $entityType;
    public Model $entity;


    /**
     * Create a new component instance.
     *
     * @param \Illuminate\Support\Collection<Member> $members
     * @param Club|League|Region $entity
     * @param class-string $entityClass
     * @return void
     *
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
     *
     */
    public function render()
    {

        // <x-member-card :members="$members" :entity="$club" entity-class="App\Models\Club" />

        return view('components.member-card');
    }
}
