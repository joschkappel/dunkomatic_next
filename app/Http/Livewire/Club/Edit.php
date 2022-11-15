<?php

namespace App\Http\Livewire\Club;

use App\Models\Club;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public $language;

    public $shortname;

    public $name;

    public $club_no;

    public $url;

    public $inactive = false;

    public Club $club;

    public $locale;

    public function rules()
    {
        return [
            'shortname' => ['required', Rule::unique('clubs')->ignore($this->club->shortname, 'shortname'), 'min:4', 'max:4'],
            'name' => 'required|max:255',
            'url' => 'nullable|starts_with:http|url|max:255',
            'club_no' => ['required', 'required', 'numeric', 'starts_with:061', 'between:'.config('dunkomatic.club_no_min').','.config('dunkomatic.club_no_max'),  Rule::unique('clubs')->ignore($this->club->club_no, 'club_no')],
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

    public function update()
    {
        $valid_data = $this->validate();

        $this->club->update($valid_data);
        Log::notice('club updated.', ['club-id' => $this->club->id]);

        session()->flash('success', 'Club modified');
    }

    public function mount($language, Club $club)
    {
        $this->locale = $language;
        $this->club = $club;
        $this->name = $club->name;
        $this->shortname = $club->shortname;
        $this->club_no = $club->club_no;
        $this->url = $club->url;
        $this->inactive = $club->inactive;
    }

    public function render()
    {
        return view('livewire.club.edit')->extends('layouts.page')->section('content');
    }
}
