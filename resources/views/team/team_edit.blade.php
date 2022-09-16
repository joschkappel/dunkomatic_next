@extends('layouts.page')

@section('content')
@php
    $team_lastmod = $team->audits()->exists() ?
                    __('audit.last', [ 'audit_created_at' => Carbon\Carbon::parse($team->audits()->latest()->first()->created_at)->locale(app()->getLocale())->isoFormat('LLL'),
                                                       'user_name' => $team->audits()->latest()->first()->user->name ?? config('app.name')] ) :
                    __('audit.unavailable') ;
@endphp
<div class="container-fluid ">
    <div class="row">
        <div class="col-sm-6 pd-2">
            <x-card-form colWidth=12 cardChangeNote="{{$team_lastmod}}"  cardTitle="{{ __('team.title.modify', ['team'=> $team->club['shortname'].' '.$team->team_no ]) }}" formAction="{{ route('team.update',['team' => $team]) }}" formMethod="PUT" >
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
                    <label for='selTday' class="col-md-4 col-form-label">@lang('team.training')</label>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
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
                    <label for='selGday' class="col-md-4 col-form-label">@lang('team.game.preferred')</label>
                    <div class="col-md-4">
                    <div class="input-group mb-3">
                        <select class='js-gday-placeholder-single js-states form-control select2 @error('preferred_game_day') is-invalid @enderror' id='selGday' name="preferred_game_day">
                            <option value="6" @if ( $team->preferred_game_day == '6' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::SATURDAY)->locale(Config::get('app.locale'))->dayName }}
                            <option value="7" @if ( $team->preferred_game_day == '7' ) selected @endif>{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::SUNDAY)->locale(Config::get('app.locale'))->dayName }}
                        </select>
                        @error('preferred_game_day')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
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
                    <label for='selGym'
                        class="col-sm-4 col-form-label">{{ __('team.gym.preferred') }}</label>
                    <div class="col-sm-6">
                    <div class="input-group mb-3">
                        <select class='js-gym-single js-states form-control select2 @error('gym_id')
                            is-invalid @enderror' id='selGym' name="gym_id">
                            @if ($team->gym()->exists())
                            <option value="{{ old('gym_id',$team->gym->id ) }}" selected >{{ old('gym_id') ? ( App\Models\Gym::find(old('gym_id'))->gym_no.' - '.App\Models\Gym::find(old('gym_id'))->name ) : ( $team->gym->gym_no.' - '.$team->gym->name ) }}</option>
                            @endif

                        </select>
                        @error('gym_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>
                    </div>
                </div>

            </x-card-form>
        </div>
        <div class="col-sm-6">
            <!-- card MEMBERS -->
            <x-member-card :members="$members" :entity="$team" entity-class="App\Models\Team" :detail="true" :collapse="false" />
            <!-- /.card -->
        </div>
    </div>
</div>
<!-- all modals here -->
<x-confirm-deletion modalId="modalDeleteMember" modalTitle="{{ __('role.title.delete') }}" modalConfirm="{{ __('role.confirm.delete') }}" deleteType="{{ trans_choice('role.member', 1) }}" />
@include('member/includes/membership_add')
@include('member/includes/membership_modify')
<!-- all modals above -->
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
            width: '100%',
            multiple: false,
            allowClear: false,
            minimumResultsForSearch: 20
        });
        $("#selGday").select2({
            placeholder: "Select preferred game day...",
            width: '100%',
            multiple: false,
            allowClear: true,
            minimumResultsForSearch: 20
        });
        $("#selTeamNo").select2({
            placeholder: "Select team number...",
            width: '100%',
            multiple: false,
            allowClear: false,
            minimumResultsForSearch: 20
        });
        $("#selGym").select2({
            placeholder: "{{ __('gym.action.select') }}...",
            width: '100%',
            multiple: false,
            allowClear: false,
            ajax: {
                url: '{{ route('gym.sb.club', ['club' => $team->club->id]) }}',
                type: "get",
                delay: 250,
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        $("button#addMembership").click(function() {
            var url =
                "{{ route('membership.team.add', ['team' => ':teamid:', 'member' => ':memberid:']) }}";
            url = url.replace(':memberid:', $(this).data('member-id'));
            url = url.replace(':teamid:', $(this).data('team-id'));
            $('#modalAddMembership_Form').attr('action', url);
            $('#modalAddMembership').modal('show');
        });
        $("button#modMembership").click(function() {
            var url = "{{ route('membership.update', ['membership' => ':membershipid:']) }}";
            url = url.replace(':membershipid:', $(this).data('membership-id'));
            var url2 = "{{ route('membership.destroy', ['membership' => ':membershipid:']) }}";
            url2 = url2.replace(':membershipid:', $(this).data('membership-id'));
            $('#hidDelUrl').val(url2);
            $('#modmemfunction').val($(this).data('function'));
            $('#modmememail').val($(this).data('email'));
            $('#modmemrole').val($(this).data('role'));
            $('#modalMembershipMod_Form').attr('action', url);
            $('#modalMembershipMod').modal('show');
        });
        $("button#deleteMember").click(function() {
            $('#modalDeleteMember_Instance').html($(this).data('member-name'));
            var url =
                "{{ route('membership.team.destroy', ['team' => $team, 'member' => ':member:']) }}";
            url = url.replace(':member:', $(this).data('member-id'));
            $('#modalDeleteMember_Form').attr('action', url);
            $('#modalDeleteMember').modal('show');
        });

    });
</script>


@stop
