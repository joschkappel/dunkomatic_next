<?php

namespace App\Http\Controllers;

use App\Models\LeagueTeamSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class LeagueSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      $sizes = LeagueTeamSize::query()->orderBy('size', 'ASC')->get();
      Log::debug('got sizes '.count($sizes));
      $response = array();

      foreach($sizes as $size){
          $response[] = array(
                "id"=>$size->size,
                "text"=>$size->description
              );
      }

      return Response::json($response);
    }

}
