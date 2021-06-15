<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use App\Models\Region;
use App\Models\Club;


use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;

use App\Notifications\ApproveUser;
use App\Notifications\InviteUser;

class MemberController extends Controller
{

      public function index()
      {
        return view('member/member_list');
      }

      public function datatable(Region $region )
      {
        $members = $region->clubs()->with('members')->get()->pluck('members.*.id')->flatten()->concat(
                      $region->leagues()->with('members')->get()->pluck('members.*.id')->flatten()
                    )->concat(
                      $region->members->pluck('id')->flatten()
                    )->unique();
        $mlist = datatables()::of(Member::whereIn('id', $members)->get());

        return $mlist
              ->rawColumns(['user_account'])
              ->addColumn('name', function ($data) {
                      return $data->lastname.', '.$data->firstname;
                  })
              ->addColumn('clubs', function ($data) {
                      return $data->memberofclubs;
                  })
              ->addColumn('leagues', function ($data) {
                      return $data->memberofleagues;
                  })
              ->addColumn('user_account', function ($data) {
                      if ($data->isuser and !$data->user->isregionadmin){
                        return '<a href="' . route('admin.user.edit', ['language'=>app()->getLocale(), 'user'=>$data->user->id]) .'"><i class="fas fa-user text-info"></i></a>';
                      };
                  })
              ->make(True);
      }

      /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function sb_region( Region $region)
      {
        Log::info('members for region '.$region->name);

        if ($region->hq == null){
            $m_ids = collect();
            foreach ( $region->childRegions as $r){
                $m_ids = $m_ids->merge($r->clubs()->pluck('id'));
            };
        } else {
            $m_ids = $region->clubs()->pluck('id');
        }

        $members = Membership::whereIn('membership_id', $m_ids)
                              ->where('membership_type',Club::class)
                              ->with('member')
                              ->get()
                              ->sortBy('member.lastname')
                              ->pluck('member.name','member.id');
        //Log::debug('got members '.count($members));

        $response = array();

        foreach($members as $k => $v){
            $response[] = array(
                  "id"=>$k,
                  "text"=>$v
                );
        }

        return Response::json($response);
      }

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

    public function store(Request $request)
    {
        Log::info(print_r($request->all(),true));
        $data = Validator::make($request->all(), Member::$createRules)
                          ->validateWithBag('err_member');

        $member = Member::create($data);

        return redirect()->back()->with('member', $member);

      }

    public function update(Request $request, Member $member)
    {
        Log::debug(print_r($request->all(),true));
        $data = Validator::make($request->all(), Member::$createRules)
                           ->validateWithBag('err_member');

        $member->update($data);

        return redirect()->back()->with('member_mod', $member);
    }
    /**
     * send a user registration invite to this member
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function invite(Member $member)
    {
      $member->notify(new InviteUser(Auth::user(), Auth::user()->region));
      return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
     public function destroy(Member $member)
     {
         $check = $member->delete();

         return Response::json($check);
     }

}
