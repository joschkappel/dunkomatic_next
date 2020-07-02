<?php

namespace App\Http\Controllers;

use App\Club;
use App\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class ClubGymController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function index(Club $club)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function create(Club $club)
    {
      return view('club/gym/gym_new', ['club' => $club]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Club $club)
    {
      $data = $request->validate( [
          'club_id' => 'required|exists:clubs,id',
          'gym_no' => 'required',
          'name' => 'required|max:64',
          'zip' => 'required|max:10',
          'street' => 'required|max:40',
          'city' => 'required|max:40',
      ]);

      $check = Gym::create($data);
      return redirect()->route('club.dashboard', ['id' => $club->id ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Club  $club
     * @param  \App\Gym  $gym
     * @return \Illuminate\Http\Response
     */
    public function show(Club $club, Gym $gym)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Club  $club
     * @param  \App\Gym  $gym
     * @return \Illuminate\Http\Response
     */
    public function edit(Club $club, Gym $gym)
    {
      Log::debug('editing gym '.$gym->id.' for club '.$club->id);
      return view('club/gym/gym_edit', ['gym' => $gym, 'club' => $club]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Club  $club
     * @param  \App\Gym  $gym
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Club $club, Gym $gym)
    {
      $data = $request->validate( [
          'gym_no' => 'required',
          'name' => 'required|max:64',
          'zip' => 'required|max:10',
          'street' => 'required|max:40',
          'city' => 'required|max:40',
      ]);

      $check = gym::where('id', $gym->id)->update($data);
      return redirect()->route('club.dashboard', ['id' => $gym->club_id ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Club  $club
     * @param  \App\Gym  $gym
     * @return \Illuminate\Http\Response
     */
    public function destroy(Club $club, Gym $gym)
    {
      Log::info('deleteing gym '.$gym->id);
      $check = Gym::where('id', $gym->id)->delete();

      return redirect()->route('club.dashboard', ['id' => $gym->club_id ]);
    }
}
