<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Club;
use App\Models\Invitation;
use App\Models\League;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Region;
use App\Models\Team;
use App\Models\User;
use App\Notifications\InviteUser;
use BenSampo\Enum\Rules\EnumValue;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Silber\Bouncer\BouncerFacade as Bouncer;

class MemberController extends Controller
{
    /**
     * display member list
     *
     * @param  string  $language
     * @param  Region  $region
     * @return \Illuminate\View\View
     */
    public function index(string $language, Region $region)
    {
        Log::info('showing member list.');

        return view('member/member_list', ['region' => $region]);
    }

    /**
     * datatables.net listing all members of a region
     *
     * @param  Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request, Region $region)
    {
        // check if for all or only current region
        if ($request->has('all')) {
            $members = Member::whereNotNull('id')->with('user', 'clubs', 'leagues', 'memberships')->get();
        } else {
            // get all leagues for all teams in thsi region
            $all_leagues = League::whereIn('id', $region->clubs()->with('teams')->without('region')->get()->pluck('teams.*.league_id')->flatten()->whereNotNull()->values())->with('teams')->get();
            $all_region_ids = $all_leagues->pluck('region_id')->unique();
            $all_league_ids = $all_leagues->pluck('id')->unique();
            $all_team_ids = $all_leagues->pluck('teams.*.id')->flatten()->values();
            $all_teams = Team::whereIn('id', $all_team_ids)->with('club', 'league')->get();
            $all_club_ids = $all_teams->pluck('club_id')->unique();

            // get members for all concerned regions, leagues and clubs
            $members = Membership::where('membership_type', Region::class)->whereIn('membership_id', $all_region_ids)->pluck('member_id');
            $members = $members->concat(Membership::where('membership_type', League::class)->whereIn('membership_id', $all_league_ids)->pluck('member_id'));
            $members = $members->concat(Membership::where('membership_type', Team::class)->whereIn('membership_id', $all_team_ids)->pluck('member_id'));
            $members = $members->concat(Membership::where('membership_type', Club::class)->whereIn('membership_id', $all_club_ids)->pluck('member_id'))->unique();
            $members = Member::whereIn('id', $members)->with('user', 'clubs', 'leagues', 'memberships')->get();
        }

        Log::info('preparing member list', ['cnt' => $members->count()]);
        $mlist = datatables()::of($members);

        return $mlist
            ->rawColumns(['user_account', 'emails', 'email2', 'phone', 'name'])
            ->addColumn('action', function ($data) {
                return '<button type="button" id="copyAddress" name="copyAddress" class="btn btn-outline-primary btn-sm m-2" data-member-id="'.$data->id.'"
                ><i class="far fa-clipboard"></i></button>';
            })
            ->addColumn('name', function ($data) {
                return '<a href="#copyAddress" id="copyAddress" name="copyAddress" data-member-id="'.$data->id.'">'.$data->lastname.', '.$data->firstname.'</a>';
            })
            ->addColumn('clubs', function ($data) {
                return $data->member_of_clubs;
            })
            ->addColumn('leagues', function ($data) {
                return $data->member_of_leagues;
            })
            ->addColumn('roles', function ($data) {
                return $data->role_in_clubs.' '.$data->role_in_leagues.' '.$data->role_in_teams.' '.$data->role_in_regions;
            })
            ->addColumn('user_account', function ($data) {
                if ($data->user != null) {
                    if (Bouncer::can('update-users')) {
                        return '<a href="'.route('admin.user.edit', ['language' => app()->getLocale(), 'user' => $data->user->id]).'"><i class="fas fa-user text-info"></i></a>';
                    } else {
                        return '<i class="fas fa-user text-info"></i>';
                    }
                } else {
                    return '';
                }
            })
            ->editColumn('emails', function ($m) {
                /* if (isset($m->email1)) {
                    return '<a href="mailto:' . $m->email1 . '" target="_blank">' . $m->email1 . '</a>';
                } else {
                    return "";
                } */
                return $m->emails;
            })
            ->editColumn('email2', function ($m) {
                if (isset($m->email2)) {
                    return '<a href="mailto:'.$m->email2.'" target="_blank">'.$m->email2.'</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('phone', function ($m) {
                if ((isset($m->mobile)) or (isset($m->phone))) {
                    $phone = isset($m->mobile) ? $m->mobile : $m->phone;

                    return '<a href="tel:'.$phone.'" target="_blank">'.$phone.'</a>';
                } else {
                    return '';
                }
            })
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function sb_region(Region $region)
    {
        if ($region->is_top_level) {
            Log::notice('getting members for top level region');
            $m_ids = collect();
            foreach ($region->childRegions as $r) {
                $m_ids = $m_ids->concat($r->clubs()->pluck('id'));
                $m_ids = $m_ids->concat($r->leagues()->pluck('id'));
            }
        } else {
            Log::notice('getting members for base level region');
            $m_ids = $region->clubs()->pluck('id');
            $m_ids = $m_ids->concat($region->leagues()->pluck('id'));
        }

        $members = Membership::whereIn('membership_id', $m_ids)
            ->whereIn('membership_type', [Club::class, League::class])
            ->with('member')
            ->get()
            ->sortBy('member.lastname')
            ->pluck('member.name', 'member.id');
        //Log::debug('got members '.count($members));

        Log::info('preparing select2 member list.', ['count' => count($members)]);
        $response = [];

        foreach ($members as $k => $v) {
            $response[] = [
                'id' => $k,
                'text' => $v,
            ];
        }

        return Response::json($response);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Club  $club
     * @return \Illuminate\Http\JsonResponse
     */
    public function sb_club(Club $club)
    {
        Log::notice('getting members for club', ['club-id' => $club->id]);

        $members = $club->members->pluck('name', 'id')
            ->sortBy('name');

        //Log::debug('got members '.count($members));

        Log::info('preparing select2 member list.', ['count' => count($members)]);
        $response = [];

        foreach ($members as $k => $v) {
            $response[] = [
                'id' => $k,
                'text' => $v,
            ];
        }

        return Response::json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $language
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($language, Member $member)
    {
        Log::info('get details of member', ['member-id' => $member->id]);

        return Response::json($member);
    }

    /**
     * store a new member in the DB
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'firstname' => 'required|max:20',
            'lastname' => 'required|max:60',
            'zipcode' => 'required|max:10',
            'city' => 'required|max:40',
            'street' => 'required|max:40',
            'mobile' => 'required_without:phone|max:40',
            'phone' => 'required_without:mobile|max:40',
            'fax' => 'max:40',
            'email1' => 'required|max:60|email:rfc,dns',
            'email2' => 'nullable|max:60|email:rfc,dns',
            'role_id' => ['required', new EnumValue(Role::class, false)],
            'function' => 'nullable|max:40',
            'email' => 'nullable|max:60|email:rfc,dns',
        ]);
        Log::info('member form data validated OK.');

        $member_id = $data['member_id'];
        unset($data['member_id']);
        $mship = [];
        $mship['role_id'] = $data['role_id'];
        unset($data['role_id']);
        $mship['function'] = $data['function'];
        unset($data['function']);
        $mship['email'] = $data['email'];
        unset($data['email']);

        if (($member_id == null) or (! Member::find($member_id)->exists())) {
            $member = Member::create($data);
            Log::notice('new member created.', ['member-id' => $member->id]);
        } else {
            $member = Member::findOrFail($member_id);
        }

        // check if a user existst
        $user = User::where('email', $member->email1)->first();
        if ($user) {
            $user->member()->associate($member);
            $user->save();
        }

        $member_id = $member->id;
        $mship['member_id'] = $member_id;

        $entity_type = $request['entity_type'];
        $entity_id = $request['entity_id'];
        if ($entity_type == Club::class) {
            $club = Club::findOrFail($entity_id);
            $club->memberships()->create($mship);

            return redirect()->route('club.dashboard', ['language' => app()->getLocale(), 'club' => $club]);
        } elseif ($entity_type == Team::class) {
            $team = Team::findOrFail($entity_id);
            $team->memberships()->create($mship);

            return redirect()->route('club.dashboard', ['language' => app()->getLocale(), 'club' => $team->club]);
        } elseif ($entity_type == League::class) {
            $league = League::findOrFail($entity_id);
            $league->memberships()->create($mship);

            return redirect()->route('league.dashboard', ['language' => app()->getLocale(), 'league' => $league]);
        } elseif ($entity_type == Region::class) {
            $region = Region::findOrFail($entity_id);
            $region->memberships()->create($mship);

            return redirect()->route('region.index', ['language' => app()->getLocale()]);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $language
     * @param  \App\Models\Member  $member
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, $language, Member $member)
    {
        if (Arr::has($request->input(), 'member-club')) {
            $entity_type = Club::class;
            $entity_id = $request->input('member-club');
            $memberships = $member->memberships->where('membership_type', Club::class)->where('membership_id', $entity_id);
            $add_url = route('membership.club.add', ['club' => $entity_id, 'member' => $member]);
        } elseif (Arr::has($request->input(), 'member-league')) {
            $entity_type = League::class;
            $entity_id = $request->input('member-league');
            $memberships = $member->memberships->where('membership_type', League::class)->where('membership_id', $entity_id);
            $add_url = route('membership.league.add', ['league' => $entity_id, 'member' => $member]);
        } elseif (Arr::has($request->input(), 'member-region')) {
            $entity_type = Region::class;
            $entity_id = $request->input('member-region');
            $memberships = $member->memberships->where('membership_type', Region::class)->where('membership_id', $entity_id);
            $add_url = route('membership.region.add', ['region' => $entity_id, 'member' => $member]);
        } elseif (Arr::has($request->input(), 'member-team')) {
            $entity_type = Team::class;
            $entity_id = $request->input('member-team');
            $memberships = $member->memberships->where('membership_type', Team::class)->where('membership_id', $entity_id);
            $add_url = route('membership.team.add', ['team' => $entity_id, 'member' => $member]);
        }

        Log::info('editing member.', ['member-id' => $member->id]); // ], 'entity'=> , 'id'=>]);

        return view('member/member_edit', ['member' => $member, 'memberships' => $memberships, 'add_url' => $add_url, 'entity_type' => $entity_type, 'entity_id' => $entity_id, 'backto' => URL::previous()]);
    }

    /**
     * update a member in the DB
     *
     * @param  Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Member $member)
    {
        $backto = $request['backto'];
        unset($request['backto']);
        $data = $request->validate([
            'firstname' => 'required|max:20',
            'lastname' => 'required|max:60',
            'zipcode' => 'required|max:10',
            'city' => 'required|max:40',
            'street' => 'required|max:40',
            'mobile' => 'required_without:phone|max:40',
            'phone' => 'required_without:mobile|max:40',
            'fax' => 'max:40',
            'email1' => 'required|max:60|email:rfc,dns',
            'email2' => 'nullable|max:60|email:rfc,dns',
        ]);
        Log::info('member form data validated OK.');

        $check = $member->update($data);
        $member->refresh();
        Log::notice('member updated.', ['member-id' => $member->id]);

        return redirect($backto);
    }

    /**
     * send a user registration invite to this member
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invite(Member $member)
    {
        // cehck if invite existsts already
        if (($member->invitation()->exists()) or (Invitation::where('email_invitee', $member->email1)->exists())) {
            // do nothing return false
            return Redirect::back()->with(['error' => __('club.invitation.exists')]);
        } else {
            // create invitation
            DB::transaction(function () use ($member) {
                $invite = Invitation::create(['email_invitee' => $member->email1]);
                Auth::user()->invitations()->save($invite);
                $member->invitation()->save($invite);
                session('cur_region')->invitations()->save($invite);

                $member->notify(new InviteUser($invite));
                Log::info('[NOTIFICATION] invite user.', ['invitation' => $invite]);
            });

            return Redirect::back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Member $member)
    {
        // first remove all memberships
        foreach ($member->memberships as $mship) {
            $mship->delete();
        }
        // delete the member now
        if ($member->user()->exists()) {
            // unlink from user
            $user = $member->user;
            $user->member()->dissociate();
            $user->save();
            Log::notice('[] member deleted - associated user kept', ['user-id' => $user->id, 'member-id' => $member->id]);
        }
        $check = $member->delete();
        Log::notice('member deleted', ['member-id' => $member->id]);

        return Response::json(['deleted' => $check]);
    }
}
