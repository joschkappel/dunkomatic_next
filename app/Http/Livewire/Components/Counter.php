<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class Counter extends Component
{
    public $count;

    protected $listeners = ['updateCount'];

    public function updateCount($count)
    {
        $this->count = $count;
    }

    public function mount($count)
    {
        $this->count = $count;
    }
}
