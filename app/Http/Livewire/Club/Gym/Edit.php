<?php

namespace App\Http\Livewire\Club\Gym;

use Livewire\Component;
use App\Models\Gym;
use Illuminate\Support\Facades\Log;

class Edit extends Component
{
    public Gym $gym;

    public $gym_no;

    public $name;

    public $zip;

    public $city;

    public $street;

    public function rules()
    {
        return [
            'gym_no' => [
                'required', 'integer', 'between:1,9'
            ],
            'name' => 'required|max:64',
            'zip' => 'required|max:10',
            'street' => 'required|max:40',
            'city' => 'required|max:40',
        ];
    }
    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function update()
    {
        $valid_data = $this->validate();

        $this->gym->update($valid_data);
        Log::notice('gym updated.', ['gym-id' => $this->gym->id]);

        session()->flash('success', 'Gym modified');
        $this->emitTo('club.gym.index','refresh' );

        // redirect back to club index
        return redirect()->route('club.dashboard', ['language'=>$this->locale, 'club'=>$this->gym->club]  );
    }
    public function mount($language, Gym $gym)
    {
        $this->locale = $language;
        $this->name = $gym->name;
        $this->gym_no = $gym->gym_no;
        $this->zip = $gym->zip;
        $this->city = $gym->city;
        $this->street = $gym->street;

    }

    public function render()
    {
        return view('livewire.club.gym.edit')->extends('layouts.page')->section('content');
    }
}
