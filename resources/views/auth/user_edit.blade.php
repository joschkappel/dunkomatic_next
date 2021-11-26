@extends('layouts.page')

@section('plugins.ICheck', true)

@section('content')
<x-card-form cardTitle="{{ __('auth.title.edit') }}" formAction="{{ route('admin.user.allowance', ['language'=>app()->getLocale(), 'user'=>$user]) }}" formMethod="PUT">
    <div class="form-group row">
        <label for="title" class="col-sm-4 col-form-label">@lang('auth.full_name')</label>
        <div class="col-sm-6">
            <input type="input" readonly class="form-control" id="name" value="{{ $user->name}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="title" class="col-sm-4 col-form-label">@lang('auth.email')</label>
        <div class="col-sm-6">
            <input type="input" readonly class="form-control" id="email" value="{{ $user->email}}">
        </div>
    </div>
    <div class="form-group row ">
        <label for='selRegionRole' class="col-sm-4 col-form-label">{{ __('auth.region.user.role')}}</label>
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <select class='js-role js-states form-control select2 @error('regionrole') /> is-invalid @enderror' id='selRegionRole' name="regionrole">
                @foreach ( Silber\Bouncer\Database\Role::whereIn('name',['regionadmin', 'regionobserver'])->get() as $role )
                <option value="{{$role->id}}" @if ( $user->isAn($role->name) ) selected @endif>{{ __('auth.user.role.'.$role->name) }}</option>
                @endforeach
                </select>
                @error('regionrole')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <label for='selRegions' class="col-sm-4 col-form-label">{{ trans_choice('region.region',2)}}</label>
        <div class="col-sm-6">
        <div class="input-group mb-3">
            <select class='js-region-placeholder-single form-control select2' multiple="multiple"  id='selRegions' name="region_ids[]">
                @foreach ( $user['regions'] as $k=>$v )
                    <option value="{{ $v }}" selected>{{ $k }}</option>
                @endforeach
            </select>
            @error('region_ids')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <label for='selClubRole' class="col-sm-4 col-form-label">{{ __('auth.club.user.role')}}</label>
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <select class='js-role form-control select2's id='selClubRole' name="clubrole">
                @foreach ( Silber\Bouncer\Database\Role::whereIn('name',['clubadmin', 'clubobserver'])->get() as $role )
                <option value="{{$role->id}}" @if ( $user->isAn($role->name) ) selected @endif>{{ __('auth.user.role.'.$role->name) }}</option>
                @endforeach
                </select>
                @error('clubrole')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <label for='selClubs' class="col-sm-4 col-form-label">{{ trans_choice('club.club',2)}}</label>
        <div class="col-sm-6">
            <select class='js-clubs-placeholder-single form-control select2' multiple="multiple"  id='selClubs' name="club_ids[]">
                @foreach ( $user['clubs'] as $k=>$v )
                    <option value="{{ $v }}" selected>{{ $k }}</option>
                @endforeach
            </select>

        </div>
    </div>
    <div class="form-group row ">
        <label for='selLeagueRole' class="col-sm-4 col-form-label">{{ __('auth.league.user.role')}}</label>
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <select class='js-role js-states form-control select2 @error('leaguerole') /> is-invalid @enderror' id='selLeagueRole' name="leaguerole">
                @foreach ( Silber\Bouncer\Database\Role::whereIn('name',['leagueadmin', 'leagueobserver'])->get() as $role )
                <option value="{{$role->id}}" @if ( $user->isAn($role->name) ) selected @endif>{{ __('auth.user.role.'.$role->name) }}</option>
                @endforeach
                </select>
                @error('leaguerole')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <label for='selLeagues' class="col-sm-4 col-form-label">{{ trans_choice('league.league',2)}}</label>
        <div class="col-sm-6">
        <div class="input-group mb-3">
            <select class='js-leagues-placeholder-single js-states form-control select2' multiple="multiple" id='selLeagues' name="league_ids[]">
                @foreach ($user['leagues'] as $k=>$v)
                <option value="{{$v}}" selected>{{ $k }}</option>
                @endforeach
            </select>
            </div>
        </div>
    </div>
</x-card-form>
@endsection

@section('js')
<script>
    $(function() {
        $('#frmClose').click(function(e){
            history.back();
        });
        $(".js-role").select2({
            placeholder: "@lang('auth.user.role.action.select')...",
            width: '100%',
            multiple: false,
            allowClear: true,
            minimumResultsForSearch: -1,
        });
        $("#selClubs").select2({
            placeholder: "@lang('club.action.select')...",
            width: '100%',
            multiple: true,
            allowClear: false,
            minimumResultsForSearch: -1,
            ajax: {
                    url: "{{ route('club.sb.region', ['region'=>$user->region->id])}}",
                    type: "get",
                    delay: 250,
                    processResults: function (response) {
                      return {
                        results: response
                      };
                    },
                    cache: true
                  }
        });
        $("#selRegions").select2({
            placeholder: "@lang('region.action.select')...",
            width: '100%',
            multiple: true,
            allowClear: true,
            ajax: {
                    url: "{{ route('region.admin.sb')}}",
                    type: "get",
                    delay: 250,
                    processResults: function (response) {
                      return {
                        results: response
                      };
                    },
                    cache: true
                  }
        });

        $("#selLeagues").select2({
            placeholder: "@lang('league.action.select')...",
            width: '100%',
            multiple: true,
            allowClear: false,
            minimumResultsForSearch: 20,
            ajax: {
                    url: "{{ route('league.sb.region', ['region'=>$user->region->id])}}",
                    type: "get",
                    delay: 250,
                    processResults: function (response) {
                      return {
                        results: response
                      };
                    },
                    cache: true
                  }
        });
    });


</script>


@stop
