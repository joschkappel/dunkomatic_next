@extends('layouts.page')

@section('plugins.Select2', true)
@section('plugins.ICheck', true)


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
        <label for='selClubs' class="col-sm-4 col-form-label">{{ trans_choice('club.club',2)}}</label>
        <div class="col-sm-6">
            @empty($abilities->clubs)
            <div class="input-group mb-3">
                <select class='js-clubs-placeholder-single js-states form-control select2 @error('club_ids') /> is-invalid @enderror' id='selClubs' name="club_ids[]">
                </select>
                @error('club_ids')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                </div>
            @endempty
            @isset($abilities->clubs)
                <input type="input" readonly class="form-control" id="clubs" value="{{ $abilities->clubs  }}">
            @endisset
        </div>
    </div>
    <div class="form-group row ">
        <label for='selLeagues' class="col-sm-4 col-form-label">{{ trans_choice('league.league',2)}}</label>
        <div class="col-sm-6">
            @empty($abilities->leagues)
            <div class="input-group mb-3">
                <select class='js-leagues-placeholder-single js-states form-control select2 @error('league_ids') /> is-invalid @enderror' id='selLeagues' name="league_ids[]">
                </select>
                @error('league_ids')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                </div>
            @endempty
            @isset($abilities->leagues)
                    <input type="input" readonly class="form-control" id="leagues" value="{{ $abilities->leagues }}">
            @endisset
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label"></label>
        <div class="icheck-success icheck-inline ">
        <input type="checkbox" id="approved" name="approved" @if (old('approved') == 'on') checked @endif>
        <label for="approved">{{ __('Approved')}} ?</label>
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
            theme: 'bootstrap4',
            multiple: true,
            allowClear: false,
            minimumResultsForSearch: 20,
            ajax: {
                    url: "{{ route('club.sb.region', ['region'=>$user->region->id] )}}",
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
            theme: 'bootstrap4',
            multiple: true,
            allowClear: false,
            minimumResultsForSearch: 20,
            ajax: {
                    url: "{{ route('league.sb.region', ['region'=>$user->region->id] )}}",
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
