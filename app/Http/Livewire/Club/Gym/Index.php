<?php

namespace App\Http\Livewire\Club\Gym;

use App\Models\Club;
use Illuminate\Support\Collection;
use Livewire\Component;

class Index extends Component
{
    public Club $club;

    public Collection $gyms;

    protected $listeners = ['refresh'=>'reloadClub'];

    public function reloadClub()
    {
        $this->club->refresh();
        $this->gyms = $this->club->gyms;

        $this->emitTo('components.counter','updateCount', $this->gyms->count());
    }

    public function showDeleteModal($gymid)
    {
        $this->emitTo('club.gym.delete','setGym',$gymid);
    }
    public function showEditModal($gymid)
    {
        $this->emitTo('club.gym.edit','setGym',$gymid);
    }
    public function showCreateModal($clubId)
    {
        $this->emitTo('club.gym.create','setClub',$clubId);
    }


    public function mount()
    {
        $this->gyms = $this->club->gyms;
    }

    public function render()
    {
        if (isset($this->club)){
            $this->club->refresh();
        }
        return view('livewire.club.gym.index');
    }
}
