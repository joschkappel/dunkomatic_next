<?php

namespace App\Http\Livewire\Club\Gym;

use Livewire\Component;
use App\Models\Gym;

class Delete extends Component
{
    public $name;
    public $gym_no;
    public $gymId;

    protected $listeners = ['setGym' => 'refreshGym'];

    public function refreshGym($gymId)
    {
        $gym = Gym::find($gymId);
        $this->gymId = $gym->id;
        $this->name = $gym->name;
        $this->gym_no = $gym->gym_no;
        $this->dispatchBrowserEvent('openDeleteModal');

    }

    public function destroy(Gym $gym)
    {

        $gym->delete();

        $this->emitTo('club.gym.index','refresh' );
        $this->dispatchBrowserEvent('closeDeleteModal');
    }


    public function render()
    {
        
        return view('livewire.club.gym.delete');
    }
}