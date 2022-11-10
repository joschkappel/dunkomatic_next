<?php

namespace App\Http\Livewire\Club;

use App\Models\Region;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public $locale;

    public Region $region;

    public $shortname;

    public $name;

    public $club_no;

    public $url;

    public $inactive = false;

    protected $rules = [
        'shortname' => 'required|unique:clubs,shortname|min:4|max:4',
        'name' => 'required|max:255',
        'url' => 'nullable|url|max:255',
        'club_no' => 'required|unique:clubs,club_no|max:7',
        'inactive' => 'sometimes|required|boolean',
    ];

    public function updated($field)
    {
        if ($field == 'shortname') {
            $this->shortname = Str::upper($this->shortname);
        }
        $this->validateOnly($field);
    }

    public function store()
    {
        $valid_data = $this->validate();

        $club = $this->region->clubs()->create($valid_data);
        Log::notice('new club created.', ['club-id' => $club->id]);

        session()->flash('success', 'Club created');
        $this->reset(['name', 'shortname', 'club_no', 'inactive', 'url']);
    }

    public function mount($language)
    {
        $this->locale = $language;
    }

    public function render()
    {
        return view('livewire.club.create')->extends('layouts.page')->section('content');
    }
}
