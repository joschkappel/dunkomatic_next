<?php

namespace App\Http\Controllers;

use App\Models\LeagueSize;
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

      $sizes = LeagueSize::query()->orderBy('size', 'ASC')->get();
      Log::debug('got sizes '.count($sizes));
      $response = array();

      foreach($sizes as $size){
          $response[] = array(
                "id"=>$size->id,
                "text"=>$size->description
              );
      }

      return Response::json($response);
    }

}
