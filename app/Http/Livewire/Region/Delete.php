<?php

namespace App\Http\Livewire\Region;

use App\Models\Region;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Delete extends Component
{

    public Region $region;

    public function destroy()
    {
        foreach ($this->region->users() as $u) {
            $u->delete();
        }
        Log::info('region users deleted', ['region-id' => $this->region->id]);

        $cnt = $this->region->schedules()->delete();
        Log::info('region schedules deleted', ['region-id' => $this->region->id, 'cnt'=>$cnt]);

        // $region->messages()->delete();
        $cnt = $this->region->members()->delete();
        Log::info('region members deleted', ['region-id' => $this->region->id, 'cnt'=>$cnt]);

        $cnt = $this->region->memberships()->delete();
        Log::info('region memberships deleted', ['region-id' => $this->region->id]);

        $this->region->delete();
        Log::notice('region deleted', ['region-id' => $this->region->id]);

        return redirect()->route('region.index', app()->getLocale());
    }

    public function render()
    {
        return view('livewire.region.delete');
    }
}
