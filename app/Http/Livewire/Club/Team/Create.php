<?php

namespace App\Http\Livewire\Club\Team;

use App\Models\Club;
use App\Rules\GameHour;
use App\Rules\GameMinute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Create extends Component
{
    public Club $club;

    public $team_no = '2';

    public $training_day = '3';

    public $training_time;

    public $preferred_game_day = '6';

    public $preferred_game_time;

    public $gym_id;

    public $league_prev;

    public $shirt_color;

    public function rules()
    {
        return [
            'team_no' => 'required|integer|between:1,9',
            'training_day' => 'required|integer|between:1,5',
            'training_time' => ['required', 'date_format:H:i', new GameMinute, new GameHour],
            'preferred_game_day' => 'present|integer|min:6|max:7',
            'preferred_game_time' => ['required', 'date_format:H:i', new GameMinute, new GameHour],
            'gym_id' => 'sometimes|required|exists:gyms,id',
            'league_prev' => 'nullable|string|max:20',
            'shirt_color' => 'required|string|max:20',
        ];
    }

    public function store()
    {
        $valid_data = $this->validate();

        $team = $this->club->teams()->create($valid_data);

        Log::notice('new team created.', ['club-id' => $this->club->id, 'team-id' => $team->id]);

        session()->flash('success', 'Team created');
        $this->reset(['shirt_color', 'league_prev']);
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function mount($language)
    {
        $this->training_time = Carbon::now()->setHour(18)->setMinute(00)->format('H:i');
        $this->preferred_game_time = Carbon::now()->setHour(20)->setMinute(00)->format('H:i');
        $this->locale = $language;
    }

    public function render()
    {
        return view('livewire.club.team.create')->extends('layouts.page')->section('content');
    }
}
