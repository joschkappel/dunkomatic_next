<?php

namespace App\Http\Livewire\Club\Gym;

use App\Models\Club;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{

    public $allowed_gymno;

    public Club $club;

    public $gym_no;

    public $name;

    public $zip;

    public $city;

    public $street;

    public $gym_nos = array();


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

    public function store()
    {
        $valid_data = $this->validate();

        $gym = $this->club->gyms()->create($valid_data);
        Log::notice('new gym created.', ['club-id' => $this->club->id, 'gym-id' => $gym->id]);

        // refresh model and reset props
        $this->club->refresh();
        $this->gym_nos = array_diff($this->allowed_gymno, $this->club->gyms->pluck('gym_no')->toarray());
        $gymcnt = $gym->club->gyms->count();

        $this->emitTo('club.gym.index','refresh' );

        $this->reset('gym_no','name','city','zip','street');

        $this->dispatchBrowserEvent('closeCreateModal');
    }

    public function loadGyms()
    {
        $this->allowed_gymno = config('dunkomatic.allowed_gym_nos');
        $this->gym_nos = array_diff($this->allowed_gymno, $this->club->gyms->pluck('gym_no')->toarray());
        $e = $this->emit('loadGymNos', $this->gym_nos);
    }
    public function mount(Club $club)
    {
        $this->club = $club;

    }

    public function render()
    {
        return view('livewire.club.gym.create');
    }
}
