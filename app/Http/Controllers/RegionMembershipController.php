<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use BenSampo\Enum\Rules\EnumValue;
use App\Enums\Role;

class RegionMembershipController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @param string $language
     * @param  \App\Models\Region  $region
     * @return \Illuminate\View\View
     *
     */
    public function create($language, Region $region)
    {
        Log::info('create new region member',['region-id'=>$region->id]);
        return view('member/member_new', ['entity' => $region,  'entity_type' => Region::class]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function add(Request $request, Region $region, Member $member)
    {
        $data = $request->validate([
            'selRole' => ['required', new EnumValue(Role::class, false)],
            'function'  => 'nullable|max:40',
            'email'     => 'nullable|max:60|email:rfc,dns',
        ]);
        Log::info('region membership form data validated OK.', ['region-id'=>$region->id, 'member-id'=>$member->id]);

        // create a new membership
        $ms = $region->memberships()->create([
            'role_id' => $data['selRole'],
            'member_id' => $member->id,
            'function' => $data['function'],
            'email' => $data['email']
        ]);
        Log::notice('new region membership created.', ['region-id'=>$region->id, 'member-id'=>$member->id]);

        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function update(Request $request, Region $region, Member $member)
    {
        $data = $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);
        Log::info('region membership form data validated OK.', ['region-id'=>$region->id, 'member-id'=>$member->id]);

        // get all current memberships
        $mships = $member->memberships->where('membership_type', Region::class)->where('membership_id', $region->id);
        $member_new = Member::find($data['member_id']);

        foreach ($mships as $ms) {
            //Log::debug($role);
            $ms->update(['member_id' => $member_new->id]);
        }
        Log::notice('region membership updated.', ['region-id'=>$region->id, 'member-id-old'=>$member->id, 'member-id-new'=>$member_new->id]);

        // check if old member is w/out memberships, if so delete
        if ($member_new->memberships->count() == 0) {
            $member_new->delete();
        }

        return redirect()->action(
            'RegionController@index',
            ['language' => app()->getLocale(), 'region' => $region->id]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Region $region
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function destroy(Region $region, Member $member)
    {
        $region->memberships()->where('member_id', $member->id)->delete();

        $mships = $region->memberships()->where('member_id', $member->id)->get();
        foreach ($mships as $ms) {
            $ms->delete();
            Log::notice('region membership deleted.', ['region-id'=>$region->id, 'member-id'=>$member->id]);
        }

        return redirect()->back();
    }
}
