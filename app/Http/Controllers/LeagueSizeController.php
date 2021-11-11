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
    public function index(Request $request)
    {
      if ($request['term']){
        $sizes = LeagueSize::where('description', 'like', '%'.$request['term'].'%')->orderBy('size', 'ASC')->get();
      } else {
        $sizes = LeagueSize::orderBy('size', 'ASC')->get();
      }
      Log::info('preparing select2 league size list.', ['count' => count($sizes), 'search-term' => $request['term'] ?? '' ]);

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
