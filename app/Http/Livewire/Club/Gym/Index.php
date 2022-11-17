<?php

namespace App\Http\Livewire\Club\Gym;

use App\Models\Club;
use Illuminate\Support\Collection;
use Livewire\Component;

class Index extends Component
{
    public Club $club;

    public Collection $gyms;

    public function showDeleteModal($gymid)
    {

        $e = $this->emitTo('club.gym.delete','setGym',$gymid);
    }

    public function mount()
    {
        $this->gyms = $this->club->gyms;
    }

    public function render()
    {
        return view('livewire.club.gym.index');
    }
}
