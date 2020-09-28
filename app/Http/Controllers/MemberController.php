<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Response;

class MemberController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show($language, Member $member)
    {
      //Log::debug(print_r($member,true));
      return Response::json($member);
    }

}
