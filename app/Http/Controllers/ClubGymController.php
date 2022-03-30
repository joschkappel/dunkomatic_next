<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Gym;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;


class ClubGymController extends Controller
{

    /**
     * select2 content gyms fo a club
     *
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function sb_club(Club $club)
    {
        //Log::debug(print_r($club,true));
        $gyms = $club->gyms()->get();
        Log::info('preparing select2 gyms list for club.', ['club-id' => $club->id, 'count' => count($gyms)]);

        $response = array();

        foreach ($gyms as $lgym) {
            $response[] = array(
                "id" => $lgym->id,
                "text" => $lgym->gym_no . ' - ' . $lgym->name
            );
        }
        return Response::json($response);
    }

    /**
     * select2 items for gyms of a team of a club
     *
     * @param \App\Models\Team $team
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function sb_team(Team $team)
    {
        //Log::debug(print_r($club,true));
        $club = $team->club->first();

        Log::info('preparing select2 gyms list for team.', ['team-id' => $team->id]);

        return $this->sb_club($club);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $language
     * @param  \App\Models\Club  $club
     * @return \Illuminate\View\View
     *
     */
    public function create(string $language, Club $club)
    {
        $allowed_gymno = config('dunkomatic.allowed_gym_nos');
        Log::info('create new gym for club', ['club-id' => $club->id]);
        return view('club/gym/gym_new', ['club' => $club, 'allowed_gymno' => $allowed_gymno]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function store(Request $request, Club $club)
    {
        $gym_no = $request['gym_no'];

        $data = $request->validate([
            'gym_no' => [
                'required',
                Rule::unique('gyms')->where(function ($query) use ($club, $gym_no) {
                    return $query->where('club_id', $club->id)
                        ->where('gym_no', $gym_no);
                }),
            ],
            'name' => 'required|max:64',
            'zip' => 'required|max:10',
            'street' => 'required|max:40',
            'city' => 'required|max:40',
        ]);
        Log::info('gym form data validated OK.');

        $gym = new Gym($data);
        $club->gyms()->save($gym);
        Log::notice('new gym created for club', ['club-id' => $club->id, 'gym-id' => $gym->id]);

        return redirect()->route('club.dashboard', ['language' => app()->getLocale(), 'club' => $club->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gym  $gym
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function sb_gym(Gym $gym)
    {
        $gyms = array();
        Log::info('preparing select2 gym ', ['gym-id' => $gym->id]);

        $gyms[] = array(
            "id" => $gym->id,
            "text" => $gym->gym_no . ' - ' . $gym->name
        );

        return Response::json($gyms);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $language
     * @param  \App\Models\Gym  $gym
     * @return \Illuminate\View\View
     *
     */
    public function edit($language, Gym $gym)
    {
        Log::info('editing gym.', ['gym-id' => $gym->id]);
        $gym->load('club');
        $allowed_gymno = config('dunkomatic.allowed_gym_nos');

        return view('club/gym/gym_edit', ['gym' => $gym, 'allowed_gymno' => $allowed_gymno]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gym  $gym
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function update(Request $request, Gym $gym)
    {
        $gym_no = $request['gym_no'];
        $club_id = $gym->club_id;

        Log::debug('any games for gym?',['games count'=>$gym->games->count() ]);
        $data = $request->validate([
            'gym_no' => [
                'required',
                Rule::unique('gyms')->where(function ($query) use ($club_id, $gym_no) {
                    return $query->where('club_id', $club_id)
                        ->where('gym_no', $gym_no);
                })->ignore($gym->id),
            ],
            'name' => 'required|max:64',
            'zip' => 'required|max:10',
            'street' => 'required|max:40',
            'city' => 'required|max:40',
        ]);
        Log::info('gym form data validated OK.');

        $check = $gym->update($data);
        $gym->refresh();
        Log::notice('gym updated', ['gym-id' => $gym->id]);

        return redirect()->route('club.dashboard', ['language' => app()->getLocale(), 'club' => $gym->club_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gym  $gym
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function destroy(Gym $gym)
    {
        $check = $gym->delete();
        Log::notice('gym deleted.', ['gym-id' => $gym->id]);

        return redirect()->route('club.dashboard', ['language' => app()->getLocale(), 'club' => $gym->club_id]);
    }
}
