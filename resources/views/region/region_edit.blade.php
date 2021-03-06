@extends('layouts.page')

@section('plugins.RangeSlider', true)
@section('plugins.ICheck', true)
@section('plugins.Moment', true)
@section('plugins.TempusDominus', true)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-info">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title">@lang('region.title.edit', ['region' => session('cur_region')->name ])</h3>
                    </div>
                    <!-- /.card-header -->
                    <form class="form-horizontal" action="{{ route('region.update_details', ['region' => $region]) }}"
                        method="post">
                        <div class="card-body">
                            @csrf
                            @method('PUT')

                            @if ($errors->any())

                                <div class="alert alert-danger" role="alert">
                                    @lang('Please fix the following errors')
                                </div>
                            @endif
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab"
                                        aria-controls="main" aria-selected="true">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="checks-tab" data-toggle="tab" href="#checks" role="tab"
                                        aria-controls="checks" aria-selected="false">Checks</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="reports-tab" data-toggle="tab" href="#reports" role="tab"
                                        aria-controls="reports" aria-selected="false">Reports</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="leaguestates-tab" data-toggle="tab" href="#leaguestates"
                                        role="tab" aria-controls="leaguestates" aria-selected="false">League States</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab">
                                    <div class="form-row m-2">
                                        <label for="code" class="col-sm-6 col-form-label">@lang('region.code')</label>
                                        <div class="col-sm-4">
                                            <input type="text" readonly class="form-control" id="code" name="code"
                                                value="{{ old('code') != '' ? old('code') : $region->code }}">
                                        </div>
                                    </div>
                                    <div class="form-row m-2">
                                        <label for="name" class="col-sm-6 col-form-label">@lang('region.name')</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name') != '' ? old('name') : $region->name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade " id="checks" role="tabpanel" aria-labelledby="checks-tab">
                                    <div class="form-row m-2">
                                        <label for="selNolead"
                                            class="col-sm-6 col-form-label">@lang('region.job.noleads')</label>
                                        <div class="col-sm-4">
                                            <select class='js-sel-noleads js-states form-control select2' id='selNolead'
                                                name="job_noleads">
                                                @foreach ($frequencytype as $ft)
                                                    <option @if ($ft->value == $region->job_noleads) selected @endif value="{{ $ft->value }}">
                                                        {{ $ft->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row m-2">
                                        <label for="selEmailCheck"
                                            class="col-sm-6 col-form-label">@lang('region.job.emails')</label>
                                        <div class="col-sm-4">
                                            <select class='js-sel-emailcheck js-states form-control select2'
                                                id='selEmailCheck' name="job_email_valid">
                                                @foreach ($frequencytype as $ft)
                                                    <option @if ($ft->value == $region->job_email_valid) selected @endif value="{{ $ft->value }}">
                                                        {{ $ft->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row m-2">
                                        <label for="selNotime"
                                            class="col-sm-6 col-form-label">@lang('region.job.notime')</label>
                                        <div class="col-sm-4">
                                            <select class='js-sel-notime js-states form-control select2' id='selNotime'
                                                name="job_game_notime">
                                                @foreach ($frequencytype as $ft)
                                                    <option @if ($ft->value == $region->job_game_notime) selected @endif value="{{ $ft->value }}">
                                                        {{ $ft->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row m-2">
                                        <label for="selOverlaps"
                                            class="col-sm-6 col-form-label">@lang('region.job.overlaps')</label>
                                        <div class="col-sm-4">
                                            <select class='js-sel-overlaps js-states form-control select2' id='selOverlaps'
                                                name="job_game_overlaps">
                                                @foreach ($frequencytype as $ft)
                                                    <option @if ($ft->value == $region->job_game_overlaps) selected @endif value="{{ $ft->value }}">
                                                        {{ $ft->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row m-2">
                                        <label for="game_slot"
                                            class="col-sm-6 col-form-label">@lang('region.game_slot')</label>
                                        <div class="col-sm-4">
                                            <input id="game_slot" name="game_slot" type="text" value=""></input>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">

                                    <div class="form-row m-2">
                                        <label for="selLeagueReport"
                                            class="col-sm-6 col-form-label">@lang('region.job.league_reports')</label>
                                        <div class="col-sm-4">
                                            <select class='js-sel-league-reports js-states form-control select2'
                                                id='selLeagueReport' name="job_league_reports">
                                                @foreach ($frequencytype as $ft)
                                                    <option @if ($ft->value == $region->job_league_reports) selected @endif value="{{ $ft->value }}">
                                                        {{ $ft->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row m-2">
                                        <label for="selLeagueReportFmt" class="col-sm-6 col-form-label"></label>
                                        <div class="col-sm-4">
                                            <select class='js-sel-league-reports-fmt js-states form-control select2'
                                                id='selLeagueReportFmt' name="fmt_league_reports[]">
                                                @foreach ($filetype as $ft)
                                                    <option value="{{ $ft->value }}">{{ $ft->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row m-2">
                                        <label for="selClubReport"
                                            class="col-sm-6 col-form-label">@lang('region.job.club_reports')</label>
                                        <div class="col-sm-4">
                                            <select class='js-sel-league-reports js-states form-control select2'
                                                id='selClubReport' name="job_club_reports">
                                                @foreach ($frequencytype as $ft)
                                                    <option @if ($ft->value == $region->job_club_reports) selected @endif value="{{ $ft->value }}">
                                                        {{ $ft->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row m-2">
                                        <label for="selClubReportFmt" class="col-sm-6 col-form-label"></label>
                                        <div class="col-sm-4">
                                            <select class='js-sel-club-reports-fmt js-states form-control select2'
                                                id='selClubReportFmt' name="fmt_club_reports[]">
                                                @foreach ($filetype as $ft)
                                                    <option value="{{ $ft->value }}">{{ $ft->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade " id="leaguestates" role="tabpanel"
                                    aria-labelledby="leaguestates-tab">

                                    <div class="form-row m-2">
                                        <label for="closeassignment"
                                            class="col-sm-6 col-form-label">@lang('region.close_assignment')</label>
                                        <div class="col-sm-4">
                                            <div class="input-group date" id="closeassignment" data-target-input="nearest">
                                                <input type="text"
                                                    class="form-control datetimepicker-input @error('close_assignment_at') is-invalid @enderror"
                                                    data-target="#closeassignment" name="close_assignment_at" />
                                                <div class="input-group-append" data-target="#closeassignment"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                                @error('close_assignment_at')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-row m-2">
                                        <label for="closeregistration"
                                            class="col-sm-6 col-form-label">@lang('region.close_registration')</label>
                                        <div class="col-sm-4">
                                            <div class="input-group date" id="closeregistration"
                                                data-target-input="nearest">
                                                <input type="text"
                                                    class="form-control datetimepicker-input @error('close_registration_at') is-invalid @enderror"
                                                    data-target="#closeregistration" name="close_registration_at" />
                                                <div class="input-group-append" data-target="#closeregistration"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                                @error('close_registration_at')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row m-2">
                                        <label for="closeselection"
                                            class="col-sm-6 col-form-label">@lang('region.close_selection')</label>
                                        <div class="col-sm-4">
                                            <div class="input-group date" id="closeselection" data-target-input="nearest">
                                                <input type="text"
                                                    class="form-control datetimepicker-input @error('close_selection_at') is-invalid @enderror"
                                                    data-target="#closeselection" name="close_selection_at" />
                                                <div class="input-group-append" data-target="#closeselection"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                                @error('close_selection_at')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row m-2">
                                        <label for="closescheduling"
                                            class="col-sm-6 col-form-label">@lang('region.close_scheduling')</label>
                                        <div class="col-sm-4">
                                            <div class="input-group date" id="closescheduling" data-target-input="nearest">
                                                <input type="text"
                                                    class="form-control datetimepicker-input @error('close_scheduling_at') is-invalid @enderror"
                                                    data-target="#closescheduling" name="close_scheduling_at" />
                                                <div class="input-group-append" data-target="#closescheduling"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                                @error('close_scheduling_at')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="btn-toolbar justify-content-between" role="toolbar"
                                    aria-label="Toolbar with button groups">
                                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')

    <script>
        $(function() {
            var custom_values = [60, 75, 90, 105, 120, 135, 150];
            $("#game_slot").ionRangeSlider({
                skin: "big",
                min: custom_values.indexOf(60),
                max: custom_values.indexOf(150),
                grid: true,
                step: 1,
                prettify: true,
                postfix: " minutes",
                values: custom_values,
                from: custom_values.indexOf(120)
            });

            @if (old('game_slot') != '')
                $("#game_slot").data("ionRangeSlider").update({ from: custom_values.indexOf({{ old('game_slot') }}) });
            @else
                $("#game_slot").data("ionRangeSlider").update({ from: custom_values.indexOf({{ $region->game_slot }}) });
            @endif

            moment.locale('{{ app()->getLocale() }}');

            $('#closeassignment').datetimepicker({
                useCurrent: true,
                format: 'L',
                locale: '{{ app()->getLocale() }}',
                defaultDate: moment('{{ $region->close_assignment_at }}').format('L')
            });
            $('#closeregistration').datetimepicker({
                useCurrent: false,
                format: 'L',
                locale: '{{ app()->getLocale() }}',
                defaultDate: moment('{{ $region->close_registration_at }}').format('L')
            });
            $('#closeselection').datetimepicker({
                useCurrent: false,
                format: 'L',
                locale: '{{ app()->getLocale() }}',
                defaultDate: moment('{{ $region->close_selection_at }}').format('L')
            });
            $('#closescheduling').datetimepicker({
                useCurrent: false,
                format: 'L',
                locale: '{{ app()->getLocale() }}',
                defaultDate: moment('{{ $region->close_scheduling_at }}').format('L')
            });
            $("#closeassignment").on("change.datetimepicker", function(e) {
                $('#closeregistration').datetimepicker('minDate', moment(e.date).add(1, 'day'));
                $('#closeselection').datetimepicker('minDate', moment(e.date).add(2, 'day'));
                $('#closescheduling').datetimepicker('minDate', moment(e.date).add(3, 'day'));
            });
            $("#closeregistration").on("change.datetimepicker", function(e) {
                $('#closeassignment').datetimepicker('maxDate', moment(e.date).subtract(1, 'day'));
                $('#closeselection').datetimepicker('minDate', moment(e.date).add(1, 'days'));
                $('#closescheduling').datetimepicker('minDate', moment(e.date).add(2, 'days'));
            });
            $("#closeselection").on("change.datetimepicker", function(e) {
                $('#closeassignment').datetimepicker('maxDate', moment(e.date).subtract(2, 'days'));
                $('#closeregistration').datetimepicker('maxDate', moment(e.date).subtract(1, 'day'));
                $('#closescheduling').datetimepicker('minDate', moment(e.date).add(1, 'day'));
            });
            $("#closescheduling").on("change.datetimepicker", function(e) {
                $('#closeassignment').datetimepicker('maxDate', moment(e.date).subtract(3, 'days'));
                $('#closeregistration').datetimepicker('maxDate', moment(e.date).subtract(2, 'day'));
                $('#closeselection').datetimepicker('maxDate', moment(e.date).subtract(1, 'day'));
            });

            $("#selNolead").select2({
                theme: 'bootstrap4',
                multiple: false,
                allowClear: false,
            });
            $("#selNotime").select2({
                theme: 'bootstrap4',
                multiple: false,
                allowClear: false,
            });
            $("#selEmailCheck").select2({
                theme: 'bootstrap4',
                multiple: false,
                allowClear: false,
            });
            $("#selOverlaps").select2({
                theme: 'bootstrap4',
                multiple: false,
                allowClear: false,
            });
            $("#selLeagueReport").select2({
                theme: 'bootstrap4',
                multiple: false,
                allowClear: false,
            });
            $("#selLeagueReportFmt").select2({
                theme: 'bootstrap4',
                multiple: true,
                maximumSelectionLength: 2,
                language: '{{ \Str::lower(app()->getLocale()) }}',
                allowClear: false,
            });
            $("#selLeagueReportFmt").val({{ collect($region->fmt_league_reports->getFlags())->pluck('value') }})
                .change();

            $("#selClubReport").select2({
                theme: 'bootstrap4',
                multiple: false,
                allowClear: false,
            });
            $("#selClubReportFmt").select2({
                theme: 'bootstrap4',
                multiple: true,
                maximumSelectionLength: 2,
                language: '{{ \Str::lower(app()->getLocale()) }}',
                allowClear: false,
            });
            $("#selClubReportFmt").val({{ collect($region->fmt_club_reports->getFlags())->pluck('value') }})
                .change();
        });
    </script>

@endpush
