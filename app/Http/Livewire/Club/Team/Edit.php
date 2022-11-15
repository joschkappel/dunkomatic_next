<?php

namespace App\Http\Livewire\Club\Team;

use App\Models\Team;
use App\Rules\GameHour;
use App\Rules\GameMinute;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Edit extends Component
{
    public $team_no;

    public $training_day;

    public $training_time;

    public $preferred_game_day;

    public $preferred_game_time;

    public $gym_id;

    public $league_prev;

    public $shirt_color;

    public $team_lastmod;

    public $locale;

    public Team $team;

    public $members;

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

    public function updated($field)
    {
        if ($field == 'training_time') {
            if ($this->training_time == 'Invalid date') {
                $this->training_time = null;
            }
        }
        if ($field == 'preferred_game_time') {
            if ($this->preferred_game_time == 'Invalid date') {
                $this->preferred_game_time = null;
            }
        }
        $this->validateOnly($field);
    }

    public function update()
    {
        $valid_data = $this->validate();

        $this->team->update($valid_data);
        Log::notice('team updated.', ['team-id' => $this->team->id]);

        session()->flash('success', 'Team modified');
    }

    public function mount($language)
    {
        $this->locale = $language;

        $this->team_no = $this->team->team_no;
        $this->training_day = $this->team->training_day;
        $this->training_time = Carbon::createFromTimeString($this->team->training_time)->format('H:i');
        $this->preferred_game_day = $this->team->preferred_game_day;
        $this->preferred_game_time = Carbon::createFromTimeString($this->team->preferred_game_time)->format('H:i');
        $this->gym_id = $this->team->gym_id;
        $this->league_prev = $this->team->league_prev;
        $this->shirt_color = $this->team->shirt_color;

        $this->team->load('club', 'league', 'gym');
        $this->members = $this->team->members()->with('memberships')->get();

        $this->team_lastmod = $this->team->audits()->exists() ?
        __('audit.last', ['audit_created_at' => Carbon::parse($this->team->audits()->latest()->first()->created_at)->locale(app()->getLocale())->isoFormat('LLL'),
            'user_name' => $this->team->audits()->latest()->first()->user->name ?? config('app.name'), ]) :
        __('audit.unavailable');
    }

    public function render()
    {
        return view('livewire.club.team.edit', ['team' => $this->team, 'members' => $this->members])->extends('layouts.page')->section('content');
    }
}
