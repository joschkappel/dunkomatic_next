
@section('plugins.RangeSlider', true)

@extends('layouts.modal', ['modalId' => 'modalEditGame', 'modalFormId' => 'formGame', 'modalFormMethod' => 'PUT', 'stayOnSuccess' => true ])

@section('modal_content')
                            <input type="hidden" name="gym_id" id="gym_id" />
                            <input type="hidden" name="club_id_home" id="club_id_home" />
                            <input type="hidden" name="team_id_home_old" id="team_id_home_old" />
                            <input type="hidden" name="team_home" id="team_home" />
                            <input type="hidden" name="team_id_guest_old" id="team_id_guest_old" />
                            <input type="hidden" name="team_guest" id="team_guest" />
                            <input type="hidden" name="gym_no" id="gym_no" />
                            <input type="hidden" name="game_id" id="game_id" />
                            <input type="hidden" name="game_no_old" id="game_no_old" />
                            <input type="hidden" name="league" id="league" />
                            <input type="hidden" name="league_id" id="league_id" />
                            <div class="form-group row justify-content-between">
                                    <div class="col-2">
                                        <button type="button" class="btn btn-secondary" id="btnPrev"><i class="far fa-arrow-alt-circle-left"></i></button>
                                    </div>
                                    <div class="col-8">
                                        <label for="game_no" class="col-form-label">@lang('game.action.game_no')</label>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-secondary" id="btnNext"><i class="far fa-arrow-alt-circle-right"></i></button>
                                    </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <input class="form-control " id="game_no" name="game_no" type="text" value=""></input>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for='game_date_grp' class="col-sm-4 col-form-label">@lang('game.game_date')</label>
                                <div class="col-sm-8">
                                    <div class="input-group date" id="game_date_grp" data-target-input="nearest">
                                        <input type="text"
                                            class="form-control datetimepicker-input"
                                            data-target="#game_date_grp" name="game_date" id="game_date" />
                                        <div class="input-group-append" data-target="#game_date_grp"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for='game_time_grp' class="col-sm-4 col-form-label">@lang('game.game_time')</label>
                                <div class="col-sm-8">
                                    <div class="input-group date" id="game_time_grp" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#game_time_grp" name="game_time" id="game_time" />
                                        <div class="input-group-append" data-target="#game_time_grp"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for='team_id_home'
                                    class="col-sm-4 col-form-label">{{ __('game.team_home') }}</label>
                                <div class="col-sm-8">
                                @if ($league->schedule->custom_events)
                                    <div class="input-group mb-3" id="team_id_home_grp">
                                        <select class='js-team-home js-states form-control select2' id='team_id_home' name="team_id_home">
                                            <option id="selOptionTeamHome" value="" selected="selected"></option>
                                        </select>
                                    </div>
                                @else
                                    <div class="input-group mb-3" id="team_id_home_grp">
                                        <input type="text" class="form-control" type="text" readonly id='team_id_home'>
                                        </input>
                                    </div>
                                @endif
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for='selGym'
                                    class="col-sm-4 col-form-label">{{ trans_choice('gym.gym', 1) }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group mb-3" id="gym_id_grp">
                                        <select class='js-gym-single js-states form-control select2' id='selGym' name="gym_id">
                                            <option id="selOption" value="" selected="selected"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for='team_id_guest'
                                    class="col-sm-4 col-form-label">{{ __('game.team_guest') }}</label>
                                <div class="col-sm-8">
                                @if ($league->schedule->custom_events)
                                    <div class="input-group mb-3" id="team_id_guest_grp">
                                        <select class='js-team-guest js-states form-control select2' id='team_id_guest' name="team_id_guest">
                                            <option id="selOptionTeamGuest" value="" selected="selected"></option>
                                        </select>
                                    </div>
                                </div>
                                @else
                                    <div class="input-group mb-3" id="team_id_guest_grp">
                                        <input type="text" class="form-control" type="text" readonly id='team_id_guest'>
                                        </input>
                                    </div>
                                @endif
                            </div>
@endsection

@section('modal_js_data')
  gym_id: $('#selGym').find(':selected').val(),
  game_date: $('#game_date').val(),
  game_time: $('#game_time').val(),
  @if ($league->schedule->custom_events)
  team_id_home: $('#team_id_home').find(':selected').val(),
  team_id_guest: $('#team_id_guest').find(':selected').val(),
  game_no: $('#game_no').val(),
  @endif
@endsection

@section('modal_js')
            $('#game_time_grp').datetimepicker({
                format: 'LT',
                locale: '{{ app()->getLocale() }}',
                stepping: 15,
                useCurrent: false,
                disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 22, 23, 24],
                enabledHours: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
            });

            $('#game_date_grp').datetimepicker({
                format: 'L',
                locale: '{{ app()->getLocale() }}',
                useCurrent: false
            });

            $("#modalEditGame").on('show.bs.modal', function(e) {
                var urlgyms = "{{ route('gym.sb.club', ['club' => ':clubid:']) }}";
                urlgyms = urlgyms.replace(':clubid:', $("#club_id_home").val());
                var urlteams = "{{ route('league.team.sb', ['league' => ':leagueid:']) }}";
                urlteams = urlteams.replace(':leagueid:', $("#league_id").val());

                var teamhomeSelect = $('#team_id_home');
                var teamguestSelect = $('#team_id_guest');
                var gymSelect = $('#selGym');

                function getGymDataTeam ( ) {
                    var url = "{{ route('gym.sb.team', ['team' => ':teamid:']) }}";
                    var selTeam = $('#team_id_home').find(':selected');
                    url = url.replace(':teamid:', selTeam.val() );

                    $("#selGym").select2({
                        placeholder: "{{ __('gym.action.select') }}...",
                        theme: 'bootstrap4',
                        multiple: false,
                        allowClear: false,
                        dropdownParent: $('#modalEditGame'),
                        ajax: {
                            url: url,
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
                }

                function selectGymForTeam () {
                    var urlgym = "{{ route('gym.sb.gym', ['club' => ':clubid:', 'gym' => ':gymid:']) }}";
                    urlgym = urlgym.replace(':gymid:', $("#gym_id").val());
                    urlgym = urlgym.replace(':clubid:', $("#club_id_home").val());

                    $.ajax({
                        type: 'GET',
                        url: urlgym
                    }).then(function(data) {
                        // create the option and append to Select2
                        var option = new Option(data[0].text, data[0].id, true, true);
                        gymSelect.append(option).trigger('change');

                    });
                }

                function getGameData(data){
                        var urlgames = "{{ route('league.game.show_bynumber', ['league' => ':leagueid:', 'game_no' => ':gameno:']) }}";
                        urlgames = urlgames.replace(':leagueid:', $("#league_id").val());
                        urlgames = urlgames.replace(':gameno:', data.from);
                        $('.alert').hide();

                        $.ajax({
                            type: 'GET',
                            url: urlgames,
                        }).then(function(data) {
                            console.log(data);

                            $("#game_id").val(data.id);
                            $("#gym_id").val(data.gym_id);
                            $("#gym_no").val(data.gym_no);
                            var url = "{{ route('game.update', ['game' => ':game:']) }}";
                            url = url.replace(':game:', data.id);
                            $('#formGame').attr('action', url);

                            moment.locale('{{ app()->getLocale() }}');
                            var gdate = moment(data.game_date).format('L');
                            var gtime = moment(data.game_time, 'HH:mm:ss').format('LT');
                            $("#game_time").val(gtime);
                            $("#game_date").val(gdate);

                           if (data.team_guest != ''){
                                var tgoption = new Option( data.team_guest, data.team_id_guest, true, true);
                                teamguestSelect.append(tgoption).trigger('change');
                                $("#club_id_guest").val(data.club_id_guest);
                           } else {
                                teamguestSelect.val(null).trigger('change');
                                $("#club_id_guest").val(null);
                           }
                           if (data.team_home != ''){
                                var thoption = new Option( data.team_home, data.team_id_home, true, true);
                                teamhomeSelect.append(thoption).trigger('change');
                                $("#club_id_home").val(data.club_id_home);
                                getGymDataTeam( );
                                selectGymForTeam ();
                           } else {
                                teamhomeSelect.val(null).trigger('change');
                                $("#club_id_home").val(null);
                                gymSelect.val(null).trigger('change');
                           }

                        });

                }

                $("#selGym").select2({
                    placeholder: "{{ __('gym.action.select') }}...",
                    theme: 'bootstrap4',
                    multiple: false,
                    allowClear: false,
                    dropdownParent: $('#modalEditGame'),
                    ajax: {
                        url: urlgyms,
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

                @if ($league->schedule->custom_events)
                $(".js-team-home").on("change", function() {
                   getGymDataTeam( );
                });

                $("#team_id_home").select2({
                    placeholder: "select hoeme team",
                    theme: 'bootstrap4',
                    multiple: false,
                    allowClear: false,
                    dropdownParent: $('#modalEditGame'),
                    ajax: {
                        url: urlteams,
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

                var thoption = new Option($("#team_home").val(), $("#team_id_home_old").val(), true, true);
                teamhomeSelect.append(thoption).trigger('change');
                @else
                $("#team_id_home").val( $("#team_home").val() );
                @endif

                @if ($league->schedule->custom_events)
                $("#team_id_guest").select2({
                    placeholder: "select guest team",
                    theme: 'bootstrap4',
                    multiple: false,
                    allowClear: false,
                    dropdownParent: $('#modalEditGame'),
                    ajax: {
                        url: urlteams,
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
                var tgoption = new Option($("#team_guest").val(), $("#team_id_guest_old").val(), true, true);
                teamguestSelect.append(tgoption).trigger('change');
                @else
                $("#team_id_guest").val( $("#team_guest").val() );
                @endif


                $("#game_no").ionRangeSlider({
                    skin: "big",
                    min: 1,
                    max: {{ $league->size * ( $league->size-1) }} ,
                    grid: true,
                    step: 1,
                    prettify: true,
                    @if ($league->schedule->custom_events)
                        block: false,
                    @else
                        block: true,
                    @endif
                    onChange: function (data) {
                        getGameData(data);
                    },
                    onUpdate: function (data) {
                        getGameData(data);
                    }
                });

                $("#game_no").data("ionRangeSlider").update({ from: $('#game_no_old').val() });

                if ($("#club_id_home").val()) {
                    selectGymForTeam ();
                } else {
                    gymSelect.val(null).trigger('change');
                }

                $("#btnPrev").click(function() {
                    $("#game_no").data("ionRangeSlider").update({ from: $('#game_no').val()-1 });
                    if ( $('#game_no').val() == '1'){
                      $('#btnPrev').addClass('disabled');
                    } else if ( $('#game_no').val() == '{{ $league->size * ( $league->size-1) }}' ){
                        $('#btnNext').addClass('disabled')
                    } else {
                        $('#btnNext').removeClass('disabled');
                        $('#btnPrev').removeClass('disabled');
                    }
                });
                $("#btnNext").click(function() {
                    $("#game_no").data("ionRangeSlider").update({ from: parseInt($('#game_no').val())+1 });
                    if ( $('#game_no').val() == '1'){
                      $('#btnPrev').addClass('disabled');
                    } else if ( $('#game_no').val() == '{{ $league->size * ( $league->size-1) }}' ){
                        $('#btnNext').addClass('disabled')
                    } else {
                        $('#btnNext').removeClass('disabled');
                        $('#btnPrev').removeClass('disabled');
                    }

                });

            });

@endsection
