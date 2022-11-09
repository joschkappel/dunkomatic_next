<?php

namespace App\Http\Livewire\Region;

use App\Models\Region;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Create extends Component
{
    public $locale;

    public $name;

    public $code;

    public $hq;

    public $top_regions;

    protected $rules = [
        'hq' => 'sometimes|nullable|exists:regions,code',
        'name' => 'required|unique:regions,name|min:4|max:40',
        'code' => 'required|unique:regions,code|min:4|max:5',
    ];

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function store()
    {
        $data = $this->validate();

        $region = Region::create([
            'name' => $this->name,
            'code' => $this->code,
            'hq' => $this->hq,
        ]);
        Log::notice('new region created.', ['region-id' => $region->id]);

        session()->flash('success', 'Region created');

        return redirect()->route('region.index', ['language' => $this->locale]);
    }

    public function mount($language)
    {
        $this->locale = $language;
        $this->regions = Region::whereNull('hq')->get();
    }

    public function render()
    {
        return view('livewire.region.create')->extends('layouts.page')->section('content');
    }
}
