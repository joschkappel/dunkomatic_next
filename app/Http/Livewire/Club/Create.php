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

    public function rules()
    {
        return [
            'shortname' => ['required', 'unique:clubs,shortname', 'min:4', 'max:4'],
            'name' => 'required|max:255',
            'url' => 'nullable|starts_with:http|url|max:255',
            'club_no' => ['required', 'required', 'numeric', 'starts_with:061', 'between:'.config('dunkomatic.club_no_min').','.config('dunkomatic.club_no_max'), 'unique:clubs,club_no'],
            'inactive' => 'sometimes|required|boolean',
        ];
    }

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
