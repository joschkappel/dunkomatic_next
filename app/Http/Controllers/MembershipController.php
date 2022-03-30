<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Member;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;


class MembershipController extends Controller
{


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Membership $membership)
    {
        // delete role
        $membership->delete();
        Log::notice('membership deleted.',['membership-id'=>$membership->id]);

        return Response::json(['success'=>'all good'], 200);
    }

    /**
     * Add  the specified resource to storage.
     *
     * @param Request $request
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function update(Request $request, Membership $membership)
    {
        $data = $request->validate([
            'function'  => 'nullable|max:40',
            'email'     => 'nullable|max:60|email:rfc,dns',
        ]);
        Log::info('membership form data validated OK.');

        $check = $membership->update($data);
        Log::notice('membership updated.',['membership-id'=>$membership->id]);

        return redirect()->back();
    }
}
