@extends('layouts.page')

@section('content')
<x-card-form cardTitle="{{ __('auth.title.approve') }}" formAction="{{ route('admin.user.approve', ['language'=>app()->getLocale(), 'user_id'=>$user->id]) }}">
    <input type="hidden" name="user_id" value="{{ $user->id}}">
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
    <div class="form-group row">
        <label for="title" class="col-sm-4 col-form-label">@lang('auth.reason_join')</label>
        <div class="col-sm-6">
            <input type="input" readonly class="form-control" id="reason_join" value="{{ $user->reason_join}}">
        </div>
    </div>
    @isset($member)
    <div class="form-group row ">
        <label class="col-sm-10 col-form-label bg-info">@lang('auth.member_found')</label>
    </div>
    @endisset
    <div class="form-group row ">
        <div class="col-sm-4">
        </div>
        <div class="col-sm-6">
            <div class="form-group  clearfix d-flex align-items-center">
                <div class="icheck-danger d-inline">
                    <input type="checkbox" id="regionadmin" name="regionadmin">
                    <label for="regionadmin">{{__('auth.user.role.regionadmin')}}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <label for='selRegions' class="col-sm-4 col-form-label">{{ __('region.preferred')}}</label>
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <select class='js-regions-placeholder-single form-control select2 @error('region_ids') /> is-invalid @enderror' disabled id='selRegions' name="region_ids[]">
                @foreach ( $user->regions()->pluck('id','code') as $k=>$v )
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
        <div class="col-sm-4">
        </div>
        <div class="col-sm-6">
            <div class="form-group  clearfix d-flex align-items-center">
                <div class="icheck-primary d-inline">
                    <input type="checkbox" id="clubadmin" name="clubadmin">
                    <label for="clubadmin">{{__('auth.user.role.clubadmin')}}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <label for='selClubs' class="col-sm-4 col-form-label">{{ __('club.preferred')}}</label>
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <select class='js-clubs-placeholder-single js-states form-control select2' multiple="multiple" id='selClubs' name="club_ids[]">
                @foreach ( $user->clubs()->pluck('id','shortname') as $k=>$v )
                    <option value="{{ $v }}" selected>{{ $k }}</option>
                @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <div class="col-sm-4">
        </div>
        <div class="col-sm-6">
            <div class="form-group  clearfix d-flex align-items-center">
                <div class="icheck-info d-inline">
                    <input type="checkbox" id="leagueadmin" name="leagueadmin">
                    <label for="leagueadmin">{{__('auth.user.role.leagueadmin')}}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <label for='selLeagues' class="col-sm-4 col-form-label">{{ __('league.preferred')}}</label>
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <select class='js-leagues-placeholder-single js-states form-control select2' multiple="multiple" id='selLeagues' name="league_ids[]">
                @foreach ($user->leagues()->pluck('id','shortname') as $k=>$v)
                <option value="{{$v}}" selected>{{ $k }}</option>
                @endforeach
                </select>
                @error('league_ids')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label"></label>
        <div class="col-sm-6">
            <div class="form-group  clearfix d-flex align-items-center">
                <div class="icheck-success d-inline ">
                    <input type="checkbox" id="approved" name="approved" @if (old('approved') == 'on') checked @endif>
                    <label for="approved">{{ __('Approved')}} ?</label>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="reason_reject" class="col-sm-4 col-form-label">@lang('auth.reason_reject')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('reason_reject') is-invalid @enderror" id="reason_reject" name="reason_reject" placeholder="@lang('auth.reason_reject')" value="{{ old('reason_reject') }}">
            @error('reason_reject')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
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
        $("#selClubs").select2({
            placeholder: "@lang('club.action.select')...",
            width: '100%',
            multiple: true,
            allowClear: true,
            ajax: {
                    url: "{{ route('club.sb.region', ['region'=>session('cur_region')] )}}",
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
            allowClear: true,
            ajax: {
                    url: "{{ route('league.sb.region', ['region'=>session('cur_region')] )}}",
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
