<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use OwenIt\Auditing\Models\Audit;

use Silber\Bouncer\BouncerFacade as Bouncer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

use App\Notifications\RejectUser;
use App\Notifications\ApproveUser;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * display view with all users of a region
     *
     * @param string $language
     * @param Region $region
     * @return \Illuminate\View\View
     */
    public function index(string $language, Region $region)
    {
        Log::info('showing user list.');
        return view('auth/user_list', ['region' => $region]);
    }

    /**
     * datatables.net listing all users of a region
     *
     * @param string $language
     * @param Region $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(string $language, Region $region)
    {
        $users = $region->users();

        Log::info('preparing user list');
        $userlist = datatables()::of($users);

        return $userlist
            ->addIndexColumn()
            ->rawColumns(['name', 'action'])
            ->addColumn('action', function ($data) {
                $state = ($data->approved_at == null) ? 'disabled' : '';
                if (Bouncer::can('update-users')) {
                    $btn = '<span data-toggle="tooltip" title="' . __('auth.user.tooltip.block', ['name' => $data->name]) . '"><button type="button" id="blockUser" name="blockUser" class="btn btn-outline-primary btn-sm" data-user-id="' . $data->id . '"
                        data-user-name="' . $data->name . '" data-toggle="modal" data-target="#modalBlockUser" ' . $state . '><i class="fas fa-ban"></i></button></span>  ';
                } else {
                    $btn = '';
                }
                if (Bouncer::can('create-users')) {
                    $btn .= '<span data-toggle="tooltip" title="' . __('auth.user.tooltip.delete', ['name' => $data->name]) . '"><button type="button" id="deleteUser" name="deleteUser" class="btn btn-outline-danger btn-sm" data-user-id="' . $data->id . '"
                       data-user-name="' . $data->name . '" data-toggle="modal" data-target="#modalDeleteUser" ><i class="fa fa-trash"></i></button></span>';
                };
                return $btn;
            })
            ->editColumn('name', function ($userlist) use ($language) {
                if ($userlist->approved_at == null) {
                    return '<i class="fas fa-exclamation-triangle text-warning"></i>  ' . $userlist->name;
                } else {
                    if (Bouncer::can('update-users')) {
                        return '<a href="' . route('admin.user.edit', ['language' => $language, 'user' => $userlist->id]) . '">' . $userlist->name . '</a>';
                    } else {
                        return $userlist->name;
                    }
                };
            })
            ->addColumn('roles', function ($u) {
                $roles = '';
                foreach ($u->getRoles() as $ur) {
                    $roles .= __('auth.user.role.' . $ur) . ', ';
                }
                return substr($roles, 0, -2);
            })
            ->addColumn('clubs', function ($u) {
                return $u->clubs()->implode('shortname', ', ');
            })
            ->addColumn('leagues', function ($u) {
                return $u->leagues()->implode('shortname', ', ');
            })
            ->addColumn('regions', function ($u) {
                return $u->regions()->implode('code', ', ');
            })
            ->editColumn('created_at', function ($userlist) use ($language) {
                if ($userlist->created_at) {
                    return array(
                        'display' => Carbon::parse($userlist->created_at)->locale($language)->isoFormat('lll'),
                        'ts' => Carbon::parse($userlist->created_at)->timestamp,
                        'filter' => Carbon::parse($userlist->created_at)->locale($language)->isoFormat('lll')
                    );
                } else {
                    return array(
                        'display' => null,
                        'ts' => 0,
                        'filter' => null
                    );
                }
            })
            ->addColumn('lastlogin_at', function ($userlist) use ($language) {
                if ($userlist->lastSuccessfulLoginAt()) {
                    return array(
                        'display' => Carbon::parse($userlist->lastSuccessfulLoginAt())->locale($language)->isoFormat('lll'),
                        'ts' => Carbon::parse($userlist->lastSuccessfulLoginAt())->timestamp,
                        'filter' => Carbon::parse($userlist->lastSuccessfulLoginAt())->locale($language)->isoFormat('lll')
                    );
                } else {
                    return array(
                        'display' => null,
                        'ts' => 0,
                        'filter' => null
                    );
                }
            })
            ->editColumn('email_verified_at', function ($userlist) use ($language) {
                if ($userlist->email_verified_at) {
                    return array(
                        'display' => Carbon::parse($userlist->email_verified_at)->locale($language)->isoFormat('lll'),
                        'ts' => Carbon::parse($userlist->email_verified_at)->timestamp,
                        'filter' => Carbon::parse($userlist->email_verified_at)->locale($language)->isoFormat('lll')
                    );
                } else {
                    return array(
                        'display' => null,
                        'ts' => 0,
                        'filter' => null
                    );
                }
            })
            ->editColumn('approved_at', function ($userlist) use ($language) {
                if ($userlist->approved_at) {
                    return array(
                        'display' => Carbon::parse($userlist->approved_at)->locale($language)->isoFormat('lll'),
                        'ts' => Carbon::parse($userlist->approved_at)->timestamp,
                        'filter' => Carbon::parse($userlist->approved_at)->locale($language)->isoFormat('lll')
                    );
                } else {
                    return array(
                        'display' => null,
                        'ts' => 0,
                        'filter' => null
                    );
                }
            })
            ->editColumn('rejected_at', function ($userlist) use ($language) {
                if ($userlist->rejected_at) {
                    return array(
                        'display' => Carbon::parse($userlist->rejected_at)->locale($language)->isoFormat('lll'),
                        'ts' => Carbon::parse($userlist->rejected_at)->timestamp,
                        'filter' => Carbon::parse($userlist->rejected_at)->locale($language)->isoFormat('lll')
                    );
                } else {
                    return array(
                        'display' => null,
                        'ts' => 0,
                        'filter' => null
                    );
                }
            })

            ->make(true);
    }

    /**
     * dispplay view with users waiting for approval for a region
     *
     * @param string $language
     * @param Region $region
     * @return \Illuminate\View\View
     */
    public function index_new(string $language, Region $region)
    {
        //  DB::enableQueryLog(); // Enable query log

        $users = $region->users()
            ->whereNull('approved_at')
            ->whereNull('rejected_at');
        //  dd(DB::getQueryLog());
        Log::info('showing waiting new user list.');

        return view('auth/users', ['users' => $users, 'region' => $region]);
    }

    /**
     * dispplay view with details of a user
     *
     * @param string $language
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function show(string $language, User $user)
    {
        $member = $user->member;
        $audits = Audit::where('user_id', $user->id)->orderBy('created_at')->get();
        Log::info('show user details', ['user-id' => $user->id]);
        return view('auth/user_profile', ['user' => $user, 'member' => $member, 'audits' => $audits]);
    }

    /**
     * dispplay view with details of a user for modification
     *
     * @param string $language
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function edit(string $language, User $user)
    {

        if ($user->approved_at == null) {
            $member = $user->member;

            $abilities['clubs'] = $user->clubs()->implode('shortname', ', ');
            $abilities['leagues'] = $user->leagues()->implode('shortname', ', ');
            $abilities['regions'] = $user->regions()->implode('code', ', ');

            Log::info('show user approval form', ['user-id' => $user->id]);
            return view('auth/user_approve', ['user' => $user, 'member' => $member, 'abilities' => $abilities]);
        } else {
            Log::info('show user edit form', ['user-id' => $user->id]);
            return view('auth/user_edit', ['user' => $user]);
        }
    }

    /**
     * update user in DB
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'locale' => ['required', 'string', 'max:2',]
        ]);
        Log::info('user form data validated OK.');

        $old_email = $user->email;
        if ($data['email'] != $user->email) {
            $data['email_verified_at'] = null;
        }

        $user->update($data);
        Log::notice('user updated.', ['user-id' => $user->id]);
        app()->setLocale($data['locale']);

        if ($data['email'] != $old_email) {
            $user->sendEmailVerificationNotification();
            //Auth::logout();
            $request->session()->flush();
            Auth::logout();
            Log::notice('user has modifed his email.', ['user-id' => $user->id, 'old-email' => $old_email, 'new-email' => $data['email']]);
            return redirect()->route('login', app()->getLocale());
        }

        return redirect()->route('home', app()->getLocale());
    }

    /**
     * approve user for a region
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request)
    {

        $user = User::findOrFail($request->user_id);

        $data = $request->validate([
            'reason_reject' =>  'exclude_if:approved,"on"|required|string',
            'region_ids' => 'sometimes|required|array',
            'region_ids.*' => 'nullable|exists:regions,id',
            'club_ids' => 'sometimes|required|array',
            'club_ids.*' => 'nullable|exists:clubs,id',
            'league_ids' => 'sometimes|required|array',
            'league_ids.*' => 'nullable|exists:leagues,id',
            'member_id' => 'sometimes|required|exists:members,id',
            'regionadmin' => 'sometimes|required|accepted',
            'clubadmin' => 'sometimes|required|accepted',
            'leagueadmin' => 'sometimes|required|accepted',
            'approved' => 'sometimes|required|accepted',
        ]);
        Log::info('approval form data validated OK.', ['user-id' => $user->id]);


        if (isset($data['approved'])) {
            $user->update(['approved_at' => now()]);
            Log::notice('user approved.', ['user-id' => $user->id]);

            $user->retract('candidate');
            if ((!isset($data['regionadmin'])) and
                (!isset($data['clubadmin'])) and
                (!isset($data['leagueadmin']))
            ) {
                $user->assign('guest');
            } else {
                if (isset($data['regionadmin'])) {
                    $user->assign('regionadmin');
                    unset($data['regionadmin']);
                } else {
                    $user->retract('regionadmin');
                }

                if (isset($data['clubadmin'])) {
                    $user->assign('clubadmin');
                    unset($data['clubadmin']);
                } else {
                    $user->retract('clubadmin');
                }

                if (isset($data['leagueadmin'])) {
                    $user->assign('leagueadmin');
                    unset($data['leagueadmin']);
                } else {
                    $user->retract('leagueadmin');
                }
            }

            // RBAC - enable region access
            /*             if (isset($data['region_ids'])) {
                foreach ($data['region_ids'] as $r) {
                    Bouncer::allow($user)->to('access', Region::find($r));
                }
                unset($data['region_ids']);
            }; */

            // RBAC - enable club access
            if (isset($data['club_ids'])) {
                foreach ($data['club_ids'] as $c) {
                    Bouncer::allow($user)->to(['access'], Club::find($c));
                }
                unset($data['club_ids']);
            };


            // RBAC - enable league access
            if (isset($data['league_ids'])) {
                foreach ($data['league_ids'] as $l) {
                    Bouncer::allow($user)->to(['access'], League::find($l));
                }
                unset($data['league_ids']);
            };


            $user->notify(new ApproveUser(session('cur_region')));
        } else {
            $user->update(['rejected_at' => now(), 'approved_at' => null, 'reason_reject' => $data['reason_reject']]);
            Log::notice('user rejected.', ['user-id' => $user->id]);

            $user->notify(new RejectUser(Auth::user(), $user, session('cur_region')));
            Log::info('user notified - REJECTUSER.', ['user-id' => $user->id]);
        }

        return redirect()->route('admin.user.index.new', ['language' => app()->getLocale(), 'region' => session('cur_region')])->withMessage('User approved successfully');
    }

    /**
     * modify user access rights
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function allowance(Request $request, User $user)
    {
        $data = $request->validate([
            'region_ids' => 'sometimes|required|array',
            'region_ids.*' => 'nullable|exists:regions,id',
            'club_ids' => 'sometimes|required|array',
            'club_ids.*' => 'nullable|exists:clubs,id',
            'league_ids' => 'sometimes|required|array',
            'league_ids.*' => 'nullable|exists:leagues,id',
            'regionadmin' => 'sometimes|required|accepted',
            'clubadmin' => 'sometimes|required|accepted',
            'leagueadmin' => 'sometimes|required|accepted',
        ]);
        Log::info('allowance form data validated OK.', ['user-id' => $user->id]);

        // RBAC need to add remove abilities
        $new_roles = [];
        if (isset($data['regionadmin'])) {
            $new_roles[] = 'regionadmin';
            unset($data['regionadmin']);
        }
        if (isset($data['clubadmin'])) {
            $new_roles[] = 'clubadmin';
            unset($data['clubadmin']);
        }
        if (isset($data['leagueadmin'])) {
            $new_roles[] = 'leagueadmin';
            unset($data['leagueadmin']);
        }

        if (count($new_roles) == 0) {
            $new_roles[] = 'guest';
        }

        Bouncer::sync($user)->roles($new_roles);

        //Bouncer::sync($user)->abilities([]);
        // RBAC - enable region access
        /*         if (isset($data['region_ids'])) {
            foreach ($data['region_ids'] as $r) {
                Bouncer::allow($user)->to('access', Region::find($r));
            }
            unset($data['region_ids']);
        }; */

        // RBAC - enable club access
        if (isset($data['club_ids'])) {
            foreach ($data['club_ids'] as $c) {
                Bouncer::allow($user)->to(['access'], Club::find($c));
            }
            unset($data['club_ids']);
        };


        // RBAC - enable league access
        if (isset($data['league_ids'])) {
            foreach ($data['league_ids'] as $l) {
                Bouncer::allow($user)->to(['access'], League::find($l));
            }
            unset($data['league_ids']);
        };
        Bouncer::refresh();

        return redirect()->route('admin.user.index', ['language' => app()->getLocale(), 'region' => session('cur_region')]);
    }

    /**
     * delete a user from the DB
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, User $user)
    {

        $user->delete();
        Bouncer::sync($user)->roles([]);
        Bouncer::sync($user)->abilities([]);
        Bouncer::refresh();
        Log::notice('user deleted.', ['user-id' => $user->id]);
        return redirect()->route('admin.user.index', ['language' => app()->getLocale(), 'region' => session('cur_region')]);
    }

    /**
     * block user access
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function block(Request $request, User $user)
    {
        $user->update(['approved_at' => null]);
        // RBAC remove old roles and set guest
        Bouncer::sync($user)->roles([]);
        Bouncer::assign('candidate')->to($user);
        Bouncer::refresh();
        Log::notice('user blocked.', ['user-id' => $user->id]);

        return redirect()->route('admin.user.index', ['language' => app()->getLocale(), 'region' => session('cur_region')]);
    }
}
