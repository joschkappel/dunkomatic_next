<div>
    @php
    $disabled = $errors->any() || empty($team_no)  || empty($training_time) || empty($training_day) || empty($preferred_game_time) || empty($shirt_color) ? true : false;
    @endphp
    <div class="container-fluid ">
        <div class="row">
            <div class="col-sm-6 pd-2">
                <x-cards.form  colWidth=12 :disabled="$disabled" cardChangeNote="{{$team_lastmod}}" cardTitle="{{ __('team.title.modify', ['team'=> $team->club['shortname'].' '.$team->team_no ]) }}" formAction="update">
                    <div class="flex flex-col m-4">
                    </div>

                    {{-- Team No --}}
                    <div class="flex flex-col m-4">
                        <label for='team_no' class="form-label">@lang('team.no')</label>
                        <div wire:ignore>
                            <select class="form-control select2" id="team_no">
                            @for ($i=1; $i<=9; $i++)
                                <option @if ($i == $team->team_no) selected @endif value="{{ $i }}">{{ $i }}</option>
                            @endfor
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col m-4">
                        <div class="row align-items-start">
                            <div class="col-6">
                                {{-- League --}}
                                <label for='league' class="form-label">{{trans_choice('league.league',1)}}</label>
                                @if ($team->league_id)
                                <input type="text" class="form-control" readonly value="{{ $team->league['shortname'] }}"></input>
                                @else
                                <input type="text" class="form-control" readonly value=""></input>
                                @endif
                            </div>

                            {{-- Previous League --}}
                            <div class="col-6">
                                <label for="league_prev" class="form-label">@lang('team.league.previous')</label>
                                <input  wire:model.debounce.500ms="league_prev" type="text" class="form-control @error('league_prev') is-invalid @else @if ($league_prev != $team->league_prev ) is-valid @endif @enderror" placeholder="@lang('team.league.previous')">
                                @error('league_prev')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Tshirt Color --}}
                    <div class="flex flex-col m-4">
                        <label for="shirt_color" class="form-label">@lang('team.shirtcolor')</label>
                        <input wire:model.debounce.500ms="shirt_color" type="text" class="form-control @error('shirt_color') is-invalid @else @if ($shirt_color != $team->shirt_color ) is-valid @endif @enderror" id="shirt_color" placeholder="@lang('team.shirtcolor')"">
                        @error('shirt_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Training day and time --}}
                    {{-- dayOfWeek returns a number between 0 (sunday) and 6 (saturday) --}}
                    {{-- dayOfWeekIso returns a number between 1 (monday) and 7 (sunday) --}}
                    <div class="flex flex-col m-4">
                        <label for='training_day' class="form-label">@lang('team.training')</label>
                        <div class="row align-items-start">
                            <div class="col-8">
                                <div wire:ignore>
                                    <select class="form-control select2" id="training_day">
                                        <option @if ( $training_day == '1' ) selected @endif value="1">{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::MONDAY)->locale(Config::get('app.locale'))->dayName }}</option>
                                        <option @if ( $training_day == '2' ) selected @endif value="2">{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::TUESDAY)->locale(Config::get('app.locale'))->dayName }}</option>
                                        <option @if ( $training_day == '3' ) selected @endif value="3">{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::WEDNESDAY)->locale(Config::get('app.locale'))->dayName }}</option>
                                        <option @if ( $training_day == '4' ) selected @endif value="4">{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::THURSDAY)->locale(Config::get('app.locale'))->dayName }}</option>
                                        <option @if ( $training_day == '5' ) selected @endif value="5">{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::FRIDAY)->locale(Config::get('app.locale'))->dayName }}</option>
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group date" id="grp_training_time" data-target-input="nearest">
                                    <input wire:model.debounce.500ms="training_time" type="text" class="form-control  datetimepicker-input  @error('training_time') is-invalid @enderror"  id="training_time"  data-target="#grp_training_time">
                                    <div  class="input-group-append" data-target="#grp_training_time" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                    @error('training_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Preferred game day and time --}}
                    <div class="flex flex-col m-4">
                        <label for='preferred_game_day' class="form-label">@lang('team.game.preferred')</label>
                        <div class="row align-items-start">
                            <div class="col-8">
                                <div wire:ignore>
                                    <select  class="form-control select2" id="preferred_game_day">
                                        <option @if ($preferred_game_day == '6' ) selected @endif value="6">{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::SATURDAY)->locale(Config::get('app.locale'))->dayName }}</option>
                                        <option @if ($preferred_game_day == '7' ) selected @endif value="7">{{ Carbon\Carbon::now()->startOfWeek(Carbon\Carbon::SUNDAY)->locale(Config::get('app.locale'))->dayName }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group date" id="grp_preferred_game_time" data-target-input="nearest">
                                    <input wire:model.debounce.500ms="preferred_game_time" type="text" class="form-control datetimepicker-input @error('preferred_game_time') is-invalid @enderror" data-target="#grp_preferred_game_time" id="preferred_game_time"/>
                                    <div class="input-group-append" data-target="#grp_preferred_game_time" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                    @error('preferred_game_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Preferred gym --}}
                    <div wire:ignore class="flex flex-col m-4">
                        <label for='gym_id' class="form-label">{{ __('team.gym.preferred') }}</label>
                        <div wire:ignore> {{-- [tl! highlight] --}}
                            <div class="input-group">
                                <select  class="form-control select2" id="gym_id">
                                    @foreach ($team->club->gyms as $g )
                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </x-cards.form>
            </div>
            <div class="col-sm-6">
                <!-- card MEMBERS -->
                <x-member-card :members="$members" :entity="$team" entity-class="App\Models\Team" />
                <!-- /.card -->
            </div>
        </div>
    </div>
    <!-- all modals here -->
    <x-confirm-deletion modalId="modalDeleteMember" modalTitle="{{ __('role.title.delete') }}" modalConfirm="{{ __('role.confirm.delete') }}" deleteType="{{ trans_choice('role.member', 1) }}" />

    <!-- all modals above -->
</div>

@push('js')
  <script>
    $(document).ready(function(){
        $("#team_no").select2({
            placeholder: "{{ __('team.select.teamno') }}...",
            width: '100%',
            multiple: false,
            allowClear: false
        });
        $('#team_no').on('change', function (e) {
            var data = $('#team_no').select2("val");
            @this.set('team_no', data);
        });
        $('#grp_training_time').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
            userCurrent: false,
            disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 23, 24],
            enabledHours: [13, 14, 15, 16, 17, 18, 19, 20, 21, 22],
        });
        $("#training_time").val('{{$training_time}}');
        $('#grp_training_time').on('change.datetimepicker', function(e) {
            if (typeof e.date !== 'undefined') {
                var time = e.date.format('HH:mm');
                @this.set('training_time', time);
            }
        });
        $("#training_day").select2({
            placeholder: "{{ __('team.select.trainingday') }}...",
            width: '100%',
            multiple: false,
            allowClear: false,
        });
        $("#preferred_game_day").select2({
            placeholder: "{{ __('team.select.gameday') }}...",
            width: '100%',
            multiple: false,
            allowClear: false,
        });
        $('#preferred_game_day').on('change.select2', function (e) {
            var data = $('#preferred_game_day').select2("val");
            @this.set('preferred_game_day', data);
        });
        $('#grp_preferred_game_time').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
            userCurrent: false,
            disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 22, 23, 24],
            enabledHours: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
        });
        $("#preferred_game_time").val('{{$preferred_game_time}}' );
        $('#grp_preferred_game_time').on('change.datetimepicker', function(e) {
            if (typeof e.date !== 'undefined') {
                var time = e.date.format('HH:mm');
                @this.set('preferred_game_time', time);
            }
        });
        $("#gym_id").select2({
            placeholder: "{{ __('gym.action.select') }}...",
            width: '100%',
            multiple: false,
            allowClear: true
        });
        $('#gym_id').on('change', function (e) {
            var data = $('#gym_id').select2("val");
            @this.set('gym_id', data);
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
@endpush
