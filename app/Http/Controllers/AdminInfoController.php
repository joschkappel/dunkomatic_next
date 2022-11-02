<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminInfoController extends Controller
{
    public function users_byproviders_chart()
    {
        Log::info('collecting users by providers chart data.');
        $data = [];
        $data['labels'] = [];
        $datasets = [];

        $rs = DB::table('users')->select('provider', DB::raw('count(*) as total'))->groupBy('provider')->get();
        // initialize dataset 0
        foreach (  LeagueAgeType::getValues() as $at) {
            $data['labels'][] = LeagueAgeType::getDescription(LeagueAgeType::coerce($at));
            $datasets[0]['data'][] = $rs[$at]->total ?? 0;
        }
        $datasets[0]['backgroundColor'] = ['hsl(0, 100%, 60%)', 'hsl(0, 100%, 40%)', 'hsl(0, 100%, 20%)'];

        $data['datasets'] = $datasets;

        // Log::debug(print_r($data,true));

        return Response::json($data);
    }
