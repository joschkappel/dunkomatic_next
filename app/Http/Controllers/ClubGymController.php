<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;


class ClubGymController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function index(Club $club)
    {
        //
    }

    public function sb_club(Club $club)
    {
      //Log::debug(print_r($club,true));
      $gyms = $club->gyms()->get();

      Log::debug('got gyms '.count($gyms));
      $response = array();

      foreach($gyms as $lgym){
        $response[] = array(
            "id"=>$lgym->id,
            "text"=>$lgym->gym_no.' - '.$lgym->name
            );

      }
      return Response::json($response);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function create($language, Club $club)
    {
      $allowed_gymno = config('dunkomatic.allowed_gym_nos');
      return view('club/gym/gym_new', ['club' => $club, 'allowed_gymno' => $allowed_gymno]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Club $club)
    {
      $club_id = $request['club_id'];
      $gym_no = $request['gym_no'];

      $data = $request->validate( [
          'club_id' => 'required|exists:clubs,id',
          'gym_no' => ['required',
                        Rule::unique('gyms')->where(function ($query) use ($club_id, $gym_no) {
                            return $query->where('club_id', $club_id)
                                         ->where('gym_no', $gym_no);
                        }),],
          'name' => 'required|max:64',
          'zip' => 'required|max:10',
          'street' => 'required|max:40',
          'city' => 'required|max:40',
      ]);

      $check = Gym::create($data);
      return redirect()->route('club.dashboard', ['language'=>app()->getLocale(),'club' => $club->id ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Club  $club
     * @param  \App\Models\Gym  $gym
     * @return \Illuminate\Http\Response
     */
    public function sb_gym(Club $club, Gym $gym)
    {
        $gyms = array();

        $gym = $club->gyms()->where('id',$gym->id)->get();

        foreach ($gym as $g){
          $gyms[] = array(
            "id"=> $g->id,
            "text"=> $g->gym_no.' - '.$g->name
          );
        }
        Log::debug(print_r($gym,true));
        return Response::json($gyms);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Club  $club
     * @param  \App\Models\Gym  $gym
     * @return \Illuminate\Http\Response
     */
    public function edit( $language, Gym $gym)
    {
      Log::debug('editing gym '.$gym->id);
      $gym->load('club');
      $allowed_gymno = config('dunkomatic.allowed_gym_nos');

      return view('club/gym/gym_edit', ['gym' => $gym, 'allowed_gymno' => $allowed_gymno]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gym  $gym
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gym $gym)
    {
      $gym_no = $request['gym_no'];
      $club_id = $gym->club_id;

      $data = $request->validate( [
        'gym_no' => ['required',
                        Rule::unique('gyms')->where(function ($query) use ($club_id, $gym_no) {
                            return $query->where('club_id', $club_id)
                                        ->where('gym_no', $gym_no);
                        })->ignore($gym->id),],
          'name' => 'required|max:64',
          'zip' => 'required|max:10',
          'street' => 'required|max:40',
          'city' => 'required|max:40',
      ]);

      $check = gym::where('id', $gym->id)->update($data);
      return redirect()->route('club.dashboard', ['language'=>app()->getLocale(), 'club' => $gym->club_id ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Club  $club
     * @param  \App\Models\Gym  $gym
     * @return \Illuminate\Http\Response
     */
    public function destroy(  Gym $gym)
    {
      Log::info('deleteing gym '.$gym->id);
      $check = Gym::where('id', $gym->id)->delete();

      return redirect()->route('club.dashboard', ['language'=>app()->getLocale(), 'club' => $gym->club_id ]);
    }
}
