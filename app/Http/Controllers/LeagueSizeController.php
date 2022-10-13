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
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request['term']) {
            $sizes = LeagueSize::where('description', 'like', '%'.$request['term'].'%')->select('id', 'description as text')->orderBy('size', 'ASC')->get();
        } else {
            $sizes = LeagueSize::orderBy('size', 'ASC')->select('id', 'description as text')->get();
        }
        Log::info('preparing select2 league size list.', ['count' => count($sizes), 'search-term' => $request['term'] ?? '']);

        return Response::json($sizes->toArray());
    }
}
