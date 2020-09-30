@extends('layouts.page')

@section('plugins.Moment', true)
@section('plugins.TempusDominus', true)
@section('plugins.Select2', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('team.title.new', ['club' => $club->shortname]) </h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('club.team.store',['club' => $club]) }}" method="POST">
                    <div class="card-body">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="club_id" value="{{ $club->id }}">
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif

                        <div class="form-group row ">
                            <label for='selTeamNo' class="col-sm-4 col-form-label">@lang('team.no')</label>
                            <div class="col-sm-6">
                                <select class='js-teamno-placeholder-single js-states form-control select2 @error('team_no') /> is-invalid @enderror' id='selTeamNo' name="team_no">
                                @for ($i=1; $i<=9; $i++)
                                  <option @if ($i == old('team_no')) selected @endif value="{{ $i }}">{{ $i }}</option>
                                @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for='selLeague' class="col-sm-4 col-form-label">{{ trans_choice('league.league',1)}}</label>
                            <div class="col-sm-6">
                                <select class='js-teamno-placeholder-single js-states form-control select2 @error('league_id') /> is-invalid @enderror' id='selLeague' name="league_id">
                                </select>
                                @error('league_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="league_prev" class="col-sm-4 col-form-label">@lang('team.league.previous')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('league_prev') is-invalid @enderror" id="league_prev" name="league_prev" placeholder="@lang('team.league.previous')" value="{{ old('league_prev') }}">
                                @error('league_prev')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="shirt_color" class="col-sm-4 col-form-label">@lang('team.shirtcolor')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('shirt_color') is-invalid @enderror" id="shirt_color" name="shirt_color" placeholder="@lang('team.shirtcolor')" value="{{ old('shirt_color') }}">
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
                                <select class='js-tday-placeholder-single js-states form-control select2 @error(' training_day') is-invalid @enderror' id='selTday' name="training_day">
                                  <option value="1" @if ( old('training_day') == '1' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::MONDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="2" @if ( old('training_day') == '2' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::TUESDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="3" @if ( old('training_day') == '3' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::WEDNESDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="4" @if ( old('training_day') == '4' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::THURSDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="5" @if ( old('training_day') == '5' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::FRIDAY)->locale(Config::get('app.locale'))->dayName }}
                                  </option>
                                </select>
                                @error('training_day')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-3">
                              <div class="input-group date" id="ttime" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#ttime" name="training_time" value="{{ old('training_time') }}"/>
                                <div class="input-group-append" data-target="#ttime" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for='selGday' class="col-sm-4 col-form-label">@lang('team.game.preferred')</label>
                            <div class="col-sm-3">
                                <select class='js-gday-placeholder-single js-states form-control select2 @error(' preferred_game_day') is-invalid @enderror' id='selGday' name="preferred_game_day">
                                  {{-- <option value="1" @if ( old('preferred_game_day') == '1' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::MONDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="2" @if ( old('preferred_game_day') == '2' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::TUESDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="3" @if ( old('preferred_game_day') == '3' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::WEDNESDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="4" @if ( old('preferred_game_day') == '4' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::THURSDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="5" @if ( old('preferred_game_day') == '5' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::FRIDAY)->locale(Config::get('app.locale'))->dayName }} --}}
                                  <option value="6" @if ( old('preferred_game_day') == '6' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::SATURDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="7" @if ( old('preferred_game_day') == '7' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::SUNDAY)->locale(Config::get('app.locale'))->dayName }}
                                </select>
                                @error('preferred_game_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-3">
                              <div class="input-group date" id="gtime" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#gtime" name="preferred_game_time"/>
                                <div class="input-group-append" data-target="#gtime" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for="coach_name" class="col-sm-4 col-form-label">@lang('team.coach')</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('coach_name') is-invalid @enderror" id="coach_name" name="coach_name" placeholder="@lang('team.coach')" value="{{ old('coach_name') }}">
                                @error('coach_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="coach_email" class="col-sm-4 col-form-label">@lang('team.email')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('coach_email') is-invalid @enderror" id="coach_email" name="coach_email" placeholder="@lang('team.email')" value="{{ old('coach_email') }}">
                                @error('coach_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="coach_phone1" class="col-sm-4 col-form-label">@lang('team.phone1')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('coach_phone1') is-invalid @enderror" id="coach_phone1" name="coach_phone1" placeholder="@lang('team.phone1')" value="{{ old('coach_phone1') }}">
                                @error('coach_phone1')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="coach_phone2" class="col-sm-4 col-form-label">@lang('team.phone2')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('coach_phone2') is-invalid @enderror" id="coach_phone2" name="coach_phone2" placeholder="@lang('team.phone2')" value="{{ old('coach_phone2') }}">
                                @error('coach_phone2')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function() {

        $('#ttime').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
            userCurrent: false,
            disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 23, 24],
            enabledHours: [13, 14, 15, 16, 17, 18, 19, 20, 21, 22],
        });


        $('#gtime').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
            userCurrent: false,
            disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 22, 23, 24],
            enabledHours: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
        });

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
        $("#selLeague").select2({
            placeholder: "@lang('league.action.select')...",
            theme: 'bootstrap4',
            multiple: false,
            allowClear: true,
            minimumResultsForSearch: 20,
            ajax: {
                    url: "{{ route('league.sb.club',['club' => $club])}}",
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
