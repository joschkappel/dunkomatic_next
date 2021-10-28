@extends('layouts.page')

@section('content')
<x-card-form cardTitle="{{ __('team.title.modify', ['team'=> $team->club['shortname'].' '.$team->team_no ]) }}" formAction="{{ route('team.update',['team' => $team]) }}" formMethod="PUT" >
    <div class="form-group row ">
        <label for='selTeamNo' class="col-sm-4 col-form-label">@lang('team.no')</label>
        <div class="col-sm-6">
        <div class="input-group mb-3">
            <select class='js-teamno-placeholder-single js-states form-control select2 @error('team_no') /> is-invalid @enderror' id='selTeamNo' name="team_no">
            @for ($i=1; $i<=9; $i++)
                <option @if ($i == $team->team_no) selected @endif value="{{ $i }}">{{ $i }}</option>
            @endfor
            </select>
            @error('team_no')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <label for='selLeague' class="col-sm-4 col-form-label">{{trans_choice('league.league',1)}}</label>
        <div class="col-sm-6">
                @if ($team->league_id)
                <input type="text" class="form-control" readonly value="{{ $team->league['shortname'] }}"></input>
            @else
                <input type="text" class="form-control" readonly value=""></input>
                @endif
        </div>
    </div>
    <div class="form-group row ">
        <label for="league_prev" class="col-sm-4 col-form-label">@lang('team.league.previous')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('league_prev') is-invalid @enderror" id="league_prev" name="league_prev" placeholder="@lang('team.league.previous')" value="{{ $team->league_prev }}">
            @error('league_prev')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row ">
        <label for="shirt_color" class="col-sm-4 col-form-label">@lang('team.shirtcolor')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('shirt_color') is-invalid @enderror" id="shirt_color" name="shirt_color" placeholder="@lang('team.shirtcolor')" value="{{ $team->shirt_color }}">
            @error('shirt_color')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    {{-- dayOfWeek returns a number between 0 (sunday) and 6 (saturday) --}}
    {{-- dayOfWeekIso returns a number between 1 (monday) and 7 (sunday) --}}
    <div class="form-group row ">
        <label for='selTday' class="col-sm-4 col-form-label">@lang('team.training')</label>
        <div class="col-sm-3">
        <div class="input-group mb-3">
            <select class='js-tday-placeholder-single js-states form-control select2 @error('training_day') is-invalid @enderror' id='selTday' name="training_day">
                <option value="1" @if ( $team->training_day == '1' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::MONDAY)->locale(Config::get('app.locale'))->dayName }}
                <option value="2" @if ( $team->training_day == '2' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::TUESDAY)->locale(Config::get('app.locale'))->dayName }}
                <option value="3" @if ( $team->training_day == '3' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::WEDNESDAY)->locale(Config::get('app.locale'))->dayName }}
                <option value="4" @if ( $team->training_day == '4' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::THURSDAY)->locale(Config::get('app.locale'))->dayName }}
                <option value="5" @if ( $team->training_day == '5' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::FRIDAY)->locale(Config::get('app.locale'))->dayName }}
                </option>
            </select>
            @error('training_day')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
        </div>
        <div class="col-sm-3">
            <div class="input-group date" id="ttime" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input @error('training_time') is-invalid @enderror" data-target="#ttime" name="training_time" value="{{ $team->training_time }}"/>
                <div class="input-group-append" data-target="#ttime" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
                @error('training_time')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <label for='selGday' class="col-sm-4 col-form-label">@lang('team.game.preferred')</label>
        <div class="col-sm-3">
        <div class="input-group mb-3">
            <select class='js-gday-placeholder-single js-states form-control select2 @error('preferred_game_day') is-invalid @enderror' id='selGday' name="preferred_game_day">
                {{-- <option value="1" @if ( $team->preferred_game_day == '1' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::MONDAY)->locale(Config::get('app.locale'))->dayName }}
                <option value="2" @if ( $team->preferred_game_day == '2' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::TUESDAY)->locale(Config::get('app.locale'))->dayName }}
                <option value="3" @if ( $team->preferred_game_day == '3' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::WEDNESDAY)->locale(Config::get('app.locale'))->dayName }}
                <option value="4" @if ( $team->preferred_game_day == '4' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::THURSDAY)->locale(Config::get('app.locale'))->dayName }}
                <option value="5" @if ( $team->preferred_game_day == '5' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::FRIDAY)->locale(Config::get('app.locale'))->dayName }} --}}
                <option value="6" @if ( $team->preferred_game_day == '6' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::SATURDAY)->locale(Config::get('app.locale'))->dayName }}
                <option value="7" @if ( $team->preferred_game_day == '7' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::SUNDAY)->locale(Config::get('app.locale'))->dayName }}
            </select>
            @error('preferred_game_day')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
        </div>
        <div class="col-sm-3">
            <div class="input-group date" id="gtime" data-target-input="nearest">
            <input type="text" class="form-control datetimepicker-input @error('preferred_game_time') is-invalid @enderror" data-target="#gtime" name="preferred_game_time"/>
            <div class="input-group-append" data-target="#gtime" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="far fa-clock"></i></div>
            </div>
            @error('preferred_game_time')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <label for="coach_name" class="col-sm-4 col-form-label">@lang('team.coach')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('coach_name') is-invalid @enderror" id="coach_name" name="coach_name" placeholder="@lang('team.coach')" value="{{  (old('coach_name')!='') ? old('coach_name') : $team->coach_name }}">
            @error('coach_name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row ">
        <label for="coach_email" class="col-sm-4 col-form-label">@lang('team.email')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('coach_email') is-invalid @enderror" id="coach_email" name="coach_email" placeholder="@lang('team.email')" value="{{ old('coach_email', $team->coach_email) }}">
            @error('coach_email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row ">
        <label for="coach_phone1" class="col-sm-4 col-form-label">@lang('team.phone1')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('coach_phone1') is-invalid @enderror" id="coach_phone1" name="coach_phone1" placeholder="@lang('team.phone1')" value="{{ old('coach_phone1', $team->coach_phone1) }}">
            @error('coach_phone1')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row ">
        <label for="coach_phone2" class="col-sm-4 col-form-label">@lang('team.phone2')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('coach_phone2') is-invalid @enderror" id="coach_phone2" name="coach_phone2" placeholder="@lang('team.phone2')" value="{{ old('coach_phone2', $team->coach_phone2 )}}">
            @error('coach_phone2')
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
        })

        $('#ttime').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
            userCurrent: false,
            disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 23, 24],
            enabledHours: [13, 14, 15, 16, 17, 18, 19, 20, 21, 22],
        });

        @if ( isset($team->training_time))
        var ttime = moment("{{ $team->training_time }}", 'HH:mm');
        $('input[name=training_time]').val(ttime.format('HH:mm'));
        @endif

        $('#gtime').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
            userCurrent: false,
            disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 22, 23, 24],
            enabledHours: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
        });
        var gtime = moment("{{ $team->preferred_game_time }}",'HH:mm');
        $('input[name=preferred_game_time]').val(gtime.format('HH:mm') );

        $("#selTday").select2({
            placeholder: "Select training day...",
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
            minimumResultsForSearch: 20
        });
        $("#selGday").select2({
            placeholder: "Select preferred game day...",
            theme: 'bootstrap4',
            multiple: false,
            allowClear: true,
            minimumResultsForSearch: 20
        });
        $("#selTeamNo").select2({
            placeholder: "Select team number...",
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
            minimumResultsForSearch: 20
        });

    });
</script>


@stop
