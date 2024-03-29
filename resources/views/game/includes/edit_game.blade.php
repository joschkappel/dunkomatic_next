
@extends('layouts.modal', ['modalId' => 'modalEditGame', 'modalFormId' => 'formGame', 'modalFormMethod' => 'PUT', 'stayOnSuccess' => true ])

@section('modal_content')
                            <input type="hidden" name="gym_id" id="gym_id" />
                            <input type="hidden" name="club_id_home" id="club_id_home" />
                            <input type="hidden" name="team_id_home" id="team_id_home" />
                            <input type="hidden" name="team_id_guest" id="team_id_guest" />
                            <input type="hidden" name="gym_no" id="gym_no" />
                            <input type="hidden" name="game_id" id="game_id" />
                            <input type="hidden" name="game_no_old" id="game_no_old" />
                            <input type="hidden" name="league" id="league" />
                            <input type="hidden" name="league_id" id="league_id" />
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
                                    <div class="input-group mb-3" id="team_id_home_grp">
                                        <input type="text" class="form-control" type="text" readonly id='team_home'>
                                        </input>
                                    </div>
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
                                    <div class="input-group mb-3" id="team_id_guest_grp">
                                        <input type="text" class="form-control" type="text" readonly id='team_guest'>
                                        </input>
                                    </div>
                            </div>
@endsection

@section('modal_js_data')
  gym_id: $('#selGym').find(':selected').val(),
  game_date: $('#game_date').val(),
  game_time: $('#game_time').val(),

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

                var gymSelect = $('#selGym');

                function getGymDataTeam ( ) {
                    var url = "{{ route('gym.sb.team', ['team' => ':teamid:']) }}";
                    var selTeam = $('#team_id_home').val();
                    url = url.replace(':teamid:', selTeam);
                    $('#selGym').val(null).empty().select2('destroy');

                    $("#selGym").select2({
                        placeholder: "{{ __('gym.action.select') }}...",
                        width: '100%',
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
                    var urlgym = "{{ route('gym.sb.gym', ['gym' => ':gymid:']) }}";
                    urlgym = urlgym.replace(':gymid:', $("#gym_id").val());
                    if ($("#gym_id").val() != ''){
                        $.ajax({
                            type: 'GET',
                            url: urlgym
                        }).then(function(data) {
                            // create the option and append to Select2
                            var option = new Option(data[0].text, data[0].id, true, true);
                            gymSelect.append(option).trigger('change');

                        });
                    };
                }

                function getGameData(from){
                        var urlgames = "{{ route('league.game.show_bynumber', ['league' => ':leagueid:', 'game_no' => ':gameno:']) }}";
                        urlgames = urlgames.replace(':leagueid:', $("#league_id").val());
                        urlgames = urlgames.replace(':gameno:', from);
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

                           if (data.team_guest != null){
                                $('#team_id_guest').val(data.team_id_guest);
                                $('#team_guest').val(data.team_guest);
                                $("#club_id_guest").val(data.club_id_guest);
                           } else {
                                $('#team_id_guest').val(null);
                                $('#team_guest').val(null);
                                $("#club_id_guest").val(null);
                           }
                           if (data.team_home != null){
                                $('#team_id_home').val(data.team_id_home);
                                $('#team_home').val(data.team_home);
                                $("#club_id_home").val(data.club_id_home);
                                getGymDataTeam( );
                                selectGymForTeam ();
                           } else {
                                $('#team_id_home').val(null);
                                $('#team_home').val(null);
                                $("#club_id_home").val(null);
                                gymSelect.val(null).trigger('change');
                           }

                        });

                }

                $("#selGym").select2({
                    placeholder: "{{ __('gym.action.select') }}...",
                    width: '100%',
                    multiple: false,
                    allowClear: false,
                    dropdownParent: $('#modalEditGame'),
                    ajax: {
                        url: urlgyms,
                        type: "get",
                        delay: 250,
                        processResults: function(response) {
                            console.log('got gyms for club');
                            console.log(urlgyms);
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                });

                $("#team_id_home").val( $("#team_home").val() );
                $("#team_id_guest").val( $("#team_guest").val() );

                $("#game_no").ionRangeSlider({
                    skin: "big",
                    min: 1,
                    max: {{ $league->size * ( $league->size-1) * $league->schedule->iterations }} ,
                    grid: true,
                    step: 1,
                    prettify: true,
                    block: false
                });
                $("#game_no").on("change", function() {
                    var $inp = $(this);
                    getGameData($inp.data("from"));
                });

                $("#game_no").data("ionRangeSlider").update({ from: $('#game_no_old').val() });

                if ($("#club_id_home").val()) {
                    selectGymForTeam ();
                } else {
                    gymSelect.val(null).trigger('change');
                }

            });

@endsection
