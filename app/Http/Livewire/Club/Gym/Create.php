<?php

namespace App\Http\Livewire\Club\Gym;

use App\Models\Club;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public $locale;

    public $allowed_gymno;

    public Club $club;

    public $gym_no;

    public $name;

    public $zip;

    public $city;

    public $street;

    public $gym_nos;

    public $iteration;

    public function rules()
    {
        $club = $this->club;
        $gym_no = $this->gym_no;

        return [
            'gym_no' => [
                'required', 'integer', 'between:1,9',
                Rule::unique('gyms')->where(function ($query) use ($club, $gym_no) {
                    return $query->where('club_id', $club->id)
                        ->where('gym_no', $gym_no);
                }),
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

    public function store()
    {
        $valid_data = $this->validate();

        $gym = $this->club->gyms()->create($valid_data);
        $this->club->refresh();

        Log::notice('new gym created.', ['club-id' => $this->club->id, 'gym-id' => $gym->id]);
        $this->gym_nos = array_diff($this->allowed_gymno, $this->club->gyms->pluck('gym_no')->toarray());
        $this->iteration++;
        $e = $this->emit('refreshGyms');
        $this->reset(['gym_no', 'name', 'zip', 'city', 'street']);

        session()->flash('success', 'Gym created');
    }

    public function mount($language)
    {
        $this->locale = $language;
        $this->allowed_gymno = config('dunkomatic.allowed_gym_nos');
        $this->gym_nos = array_diff($this->allowed_gymno, $this->club->gyms->pluck('gym_no')->toarray());
    }

    public function render()
    {
        return view('livewire.club.gym.create')->extends('layouts.page')->section('content');
    }
}
