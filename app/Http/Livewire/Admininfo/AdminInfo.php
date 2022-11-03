<?php

namespace App\Http\Livewire\Admininfo;

use Livewire\Component;

class AdminInfo extends Component
{
    public function render()
    {
        return view('livewire.admininfo.admin-info')->extends('layouts.page')->section('content');
    }
}
