@extends('adminlte::page')

@section('css')
<!-- Bootstrap Color Picker -->
<link href="{{ URL::asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">
@endsection

@section('plugins.Select2', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Modify team {{ $team->club['shortname']}} {{ $team->team_no}} </h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('team.update',['team' => $team]) }}" method="POST">
                    <div class="card-body">
                        <input type="hidden" name="_method" value="PUT">
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Please fix the following errors
                        </div>
                        @endif

                        <div class="form-group row ">
                            <label for='selTeamNo' class="col-sm-2 col-form-label">Team Number</label>
                            <div class="col-sm-10">
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
                        <div class="form-group row ">
                            <label for='selLeague' class="col-sm-2 col-form-label">League</label>
                            <div class="col-sm-10">
                                <select class='js-teamno-placeholder-single js-states form-control select2 @error('league_id') /> is-invalid @enderror' id='selLeague' name="league_id">
                                  <option  selected value="{{ $team->league_id }}">{{ $team->league['shortname'] }}</option>
                                </select>
                                @error('league_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="league_prev" class="col-sm-2 col-form-label">Previous League</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('league_prev') is-invalid @enderror" id="league_prev" name="league_prev" placeholder="league_prev" value="{{ $team->league_prev }}">
                                @error('league_prev')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="shirt_color" class="col-sm-2 col-form-label">Shirt Color</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('shirt_color') is-invalid @enderror" id="shirt_color" name="shirt_color" placeholder="shirt_color" value="{{ $team->shirt_color }}">
                                @error('shirt_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- dayOfWeek returns a number between 0 (sunday) and 6 (saturday) --}}
                        {{-- dayOfWeekIso returns a number between 1 (monday) and 7 (sunday) --}}
                        <div class="form-group row ">
                            <label for='selTday' class="col-sm-4 col-form-label">Training Day/Time</label>
                            <div class="col-sm-4">
                                <select class='js-tday-placeholder-single js-states form-control select2 @error(' training_day') is-invalid @enderror' id='selTday' name="training_day">
                                  <option value="1" @if ( $team->training_day == '1' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::MONDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="2" @if ( $team->training_day == '2' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::TUESDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="3" @if ( $team->training_day == '3' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::WEDNESDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="4" @if ( $team->training_day == '4' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::THURSDAY)->locale(Config::get('app.locale'))->dayName }}
                                  </option>
                                </select>
                                @error('training_day')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                              <div class="input-group date" id="ttime" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#ttime" name="training_time" value="{{ $team->training_time }}"/>
                                <div class="input-group-append" data-target="#ttime" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for='selGday' class="col-sm-4 col-form-label">Preferred Game Day/Time</label>
                            <div class="col-sm-4">
                                <select class='js-gday-placeholder-single js-states form-control select2 @error(' preferred_game_day') is-invalid @enderror' id='selGday' name="preferred_game_day">
                                  <option value="1" @if ( $team->preferred_game_day == '1' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::MONDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="2" @if ( $team->preferred_game_day == '2' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::TUESDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="3" @if ( $team->preferred_game_day == '3' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::WEDNESDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="4" @if ( $team->preferred_game_day == '4' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::THURSDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="5" @if ( $team->preferred_game_day == '5' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::FRIDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="6" @if ( $team->preferred_game_day == '6' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::SATURDAY)->locale(Config::get('app.locale'))->dayName }}
                                  <option value="7" @if ( $team->preferred_game_day == '7' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::SUNDAY)->locale(Config::get('app.locale'))->dayName }}
                                </select>
                                @error('preferred_game_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                              <div class="input-group date" id="gtime" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#gtime" name="preferred_game_time"/>
                                <div class="input-group-append" data-target="#gtime" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for="coach_name" class="col-sm-2 col-form-label">Coach</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('coach_name') is-invalid @enderror" id="coach_name" name="coach_name" placeholder="coach_name" value="{{ $team->coach_name }}">
                                @error('coach_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="coach_email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('coach_email') is-invalid @enderror" id="coach_email" name="coach_email" placeholder="coach_email" value="{{ old('coach_email', $team->coach_email) }}">
                                @error('coach_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="coach_phone1" class="col-sm-2 col-form-label">Phone</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('coach_phone1') is-invalid @enderror" id="coach_phone1" name="coach_phone1" placeholder="coach_phone1" value="{{ $team->coach_phone1 }}">
                                @error('coach_phone1')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="coach_phone2" class="col-sm-2 col-form-label">Alt. Phone</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('coach_phone2') is-invalid @enderror" id="coach_phone2" name="coach_phone2" placeholder="coach_phone2" value="{{ $team->coach_phone2 }}">
                                @error('coach_phone2')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <a class="btn btn-default btn-close" href="{{url()->previous()}}">Cancel</a>
                                <button type="submit" class="btn btn-info">Submit</button>
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
<script src="{{ URL::asset('vendor/moment/moment.min.js') }}"></script>
<script src="{{ URL::asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script>
    $(function() {

        $('#ttime').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
            userCurrent: false,
            disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 23, 24],
            enabledHours: [13, 14, 15, 16, 17, 18, 19, 20, 21, 22],
        });

        var ttime = moment("{{ $team->training_time }}", 'HH:mm');
        $('input[name=training_time]').val(ttime.format('HH:mm'));

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
            multiple: false,
            allowClear: false,
            minimumResultsForSearch: 20
        });
        $("#selGday").select2({
            placeholder: "Select preferred game day...",
            multiple: false,
            allowClear: true,
            minimumResultsForSearch: 20
        });
        $("#selTeamNo").select2({
            placeholder: "Select team number...",
            multiple: false,
            allowClear: false,
            minimumResultsForSearch: 20
        });
        $("#selLeague").select2({
            placeholder: "Select league...",
            multiple: false,
            allowClear: true,
            minimumResultsForSearch: 20,
            ajax: {
                    url: "{{ route('league.list_sel4club',['club' => $team->club_id])}}",
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
