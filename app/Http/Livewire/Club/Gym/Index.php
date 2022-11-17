<?php

namespace App\Http\Livewire\Club\Gym;

use App\Models\Club;
use Illuminate\Support\Collection;
use Livewire\Component;

class Index extends Component
{
    public Club $club;

    public Collection $gyms;

    protected $listeners = ['refresh'=>'$refresh'];

    public function showDeleteModal($gymid)
    {
        $this->emitTo('club.gym.delete','setGym',$gymid);
    }


    public function mount()
    {
        $this->club->refresh();
        $this->gyms = $this->club->gyms;
    }

    public function render()
    {
        return view('livewire.club.gym.index');
    }
}
