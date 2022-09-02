@extends('layouts.page')

@section('content')
<x-card-form colWidth=10 cardTitle="{{ __('region.title.edit', ['region' => session('cur_region')->name ]) }}" formAction="{{ route('region.update_details', ['region' => $region]) }}" formMethod="PUT">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab"
                aria-controls="main" aria-selected="true">{{ __('region.home') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="checks-tab" data-toggle="tab" href="#checks" role="tab"
                aria-controls="checks" aria-selected="false">{{ __('region.checks') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="reports-tab" data-toggle="tab" href="#reports" role="tab"
                aria-controls="reports" aria-selected="false">{{ __('region.reports') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="leaguestates-tab" data-toggle="tab" href="#leaguestates"
                role="tab" aria-controls="leaguestates" aria-selected="false">{{ __('region.leaguestates') }}</a>
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
            <div class="form-row m-2" >
                <label for="selNolead"
                    class="col-sm-6 col-form-label">@lang('region.job.noleads')</label>
                <div class="col-sm-4">
                <div class="input-group ">
                    <select class='js-sel-noleads js-states form-control select2' id='selNolead'
                        name="job_noleads">
                        @foreach ($frequencytype as $ft)
                            <option @if ($ft->value == $region->job_noleads) selected @endif value="{{ $ft->value }}">
                                {{ $ft->description }}</option>
                        @endforeach
                    </select>
                        </div>
                </div>
            </div>
            <div class="form-row m-2">
                <label for="selEmailCheck"
                    class="col-sm-6 col-form-label">@lang('region.job.emails')</label>
                <div class="col-sm-4">
                <div class="input-group ">
                    <select class='js-sel-emailcheck js-states form-control select2'
                        id='selEmailCheck' name="job_email_valid">
                        @foreach ($frequencytype as $ft)
                            <option @if ($ft->value == $region->job_email_valid) selected @endif value="{{ $ft->value }}">
                                {{ $ft->description }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
            <div class="form-row m-2">
                <label for="selNotime"
                    class="col-sm-6 col-form-label">@lang('region.job.notime')</label>
                <div class="col-sm-4">
                <div class="input-group ">
                    <select class='js-sel-notime js-states form-control select2' id='selNotime'
                        name="job_game_notime">
                        @foreach ($frequencytype as $ft)
                            <option @if ($ft->value == $region->job_game_notime) selected @endif value="{{ $ft->value }}">
                                {{ $ft->description }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
            <div class="form-row m-2">
                <label for="selOverlaps"
                    class="col-sm-6 col-form-label">@lang('region.job.overlaps')</label>
                <div class="col-sm-4">
                <div class="input-group ">
                    <select class='js-sel-overlaps js-states form-control select2' id='selOverlaps'
                        name="job_game_overlaps">
                        @foreach ($frequencytype as $ft)
                            <option @if ($ft->value == $region->job_game_overlaps) selected @endif value="{{ $ft->value }}">
                                {{ $ft->description }}</option>
                        @endforeach
                    </select>
                    </div>
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

            @foreach( App\Enums\Report::getInstances() as $rpt)
            @php
            $report = $region->report_jobs()->where('report_id', $rpt )->first();
            if (!$report){
                $report = new App\Models\ReportJob(['lastrun'=>null,'running'=>false,'lastrun_ok'=>true]);
            }
            @endphp
            <div class="form-row m-2">
                <label class="col-sm col-form-label">{{ $rpt->getReportTitle() }}</label>
                <div class="col-sm-4">
                    <input type="text" readonly class="form-control @if( $report->lastrun_ok) text-success @else text-danger @endif" id="lastrun_at"
                    value="@isset(  $report->lastrun_at) {{Carbon\Carbon::parse($report->lastrun_at)->locale(app()->getLocale())->isoFormat('LLL')}} @endisset">
                </div>
                <div class="col-sm-4">
                    <button id="btnRunJob" onclick="run_job({{$rpt->value}});" class="btn btn-secondary" @if ($report->running) disabled  @endif>Create {{$rpt->getReportTitle()}}</button>
                </div>
            </div>
            @endforeach
            <div class="form-row m-2">
                <label for="selLeagueReportFmt" class="col-sm col-form-label">@lang('region.job.league_reports.fmt')</label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <select class='js-sel-league-reports-fmt js-states form-control select2'
                            id='selLeagueReportFmt' name="fmt_league_reports[]">
                            @foreach ($filetype as $ft)
                                <option value="{{ $ft->value }}">{{ $ft->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                </div>
            </div>
            <div class="form-row m-2">
                <label for="selClubReportFmt" class="col-sm col-form-label">@lang('region.job.club_reports.fmt')</label>
                <div class="col-sm-4">
                    <div class="input-group ">
                        <select class='js-sel-club-reports-fmt js-states form-control select2'
                            id='selClubReportFmt' name="fmt_club_reports[]">
                            @foreach ($filetype as $ft)
                                <option value="{{ $ft->value }}">{{ $ft->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                </div>
            </div>
        </div>
        <div class="tab-pane fade " id="leaguestates" role="tabpanel" aria-labelledby="leaguestates-tab">
            <div class="form-row m-2">
                <label for="openselection"
                    class="col-sm-6 col-form-label">@lang('region.open_char_selection')</label>
                <div class="col-sm-4">
                    <div class="input-group date" id="openselection" data-target-input="nearest">
                        <input type="text"
                            class="form-control datetimepicker-input @error('open_selection_at') is-invalid @enderror"
                            data-target="#openselection" name="open_selection_at" />
                        <div class="input-group-append" data-target="#openselection"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('open_selection_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-row m-2">
                <label for="closeselection"
                    class="col-sm-6 col-form-label">@lang('region.close_char_selection')</label>
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
                <label for="openscheduling"
                    class="col-sm-6 col-form-label">@lang('region.open_game_scheduling')</label>
                <div class="col-sm-4">
                    <div class="input-group date" id="openscheduling"
                        data-target-input="nearest">
                        <input type="text"
                            class="form-control datetimepicker-input @error('open_scheduling_at') is-invalid @enderror"
                            data-target="#openscheduling" name="open_scheduling_at" />
                        <div class="input-group-append" data-target="#openscheduling"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('open_scheduling_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-row m-2">
                <label for="closescheduling"
                    class="col-sm-6 col-form-label">@lang('region.close_game_scheduling')</label>
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
            <div class="form-row m-2">
                <label for="closereferees"
                    class="col-sm-6 col-form-label">@lang('region.golive_league')</label>
                <div class="col-sm-4">
                    <div class="input-group date" id="closereferees" data-target-input="nearest">
                        <input type="text"
                            class="form-control datetimepicker-input @error('close_referees_at') is-invalid @enderror"
                            data-target="#closereferees" name="close_referees_at" />
                        <div class="input-group-append" data-target="#closereferees"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @error('close_referees_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-card-form>
@endsection

@push('js')

    <script>
        function run_job( jobclass ){
            console.log(jobclass);
            var url = "{{ route('region.run.job',['region'=>$region, 'job'=>':job:'])}}";
            url = url.replace(':job:', jobclass);

            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: 'POST'
                },
            });
        };

        $(function() {
            $('#frmClose').click(function(e){
                history.back();
            });



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

            $('#openselection').datetimepicker({
                useCurrent: true,
                format: 'L',
                locale: '{{ app()->getLocale() }}',
                @if( old('open_selection_at') !== null )
                    defaultDate: moment('{{ old('open_selection_at') }}', 'L')
                @else
                    @isset( $region->open_selection_at)
                        defaultDate: moment('{{ $region->open_selection_at}}')
                    @endisset
                @endif
            });
            $('#openscheduling').datetimepicker({
                useCurrent: false,
                format: 'L',
                locale: '{{ app()->getLocale() }}',
                @if( old('open_scheduling_at') !== null )
                    defaultDate: moment('{{ old('open_scheduling_at') }}', 'L')
                @else
                    @isset( $region->open_scheduling_at)
                        defaultDate: moment('{{ $region->open_scheduling_at}}')
                    @endisset
                @endif
            });
            $('#closeselection').datetimepicker({
                useCurrent: false,
                format: 'L',
                locale: '{{ app()->getLocale() }}',
                @if( old('close_selection_at') !== null )
                    defaultDate: moment('{{ old('close_selection_at') }}', 'L')
                @else
                    @isset( $region->close_selection_at)
                        defaultDate: moment('{{ $region->close_selection_at}}')
                    @endisset
                @endif
            });
            $('#closescheduling').datetimepicker({
                useCurrent: false,
                format: 'L',
                locale: '{{ app()->getLocale() }}',
                @if( old('close_scheduling_at') !== null )
                    defaultDate: moment('{{ old('close_scheduling_at') }}', 'L')
                @else
                    @isset( $region->close_scheduling_at)
                        defaultDate: moment('{{ $region->close_scheduling_at}}')
                    @endisset
                @endif
            });
            $('#closereferees').datetimepicker({
                useCurrent: false,
                format: 'L',
                locale: '{{ app()->getLocale() }}',
                @if( old('close_referees_at') !== null )
                    defaultDate: moment('{{ old('close_referees_at') }}', 'L')
                @else
                    @isset( $region->close_referees_at)
                        defaultDate: moment('{{ $region->close_referees_at}}')
                    @endisset
                @endif
            });

            $("#selNolead").select2({
                multiple: false,
                allowClear: false,
                minimumResultsForSearch: Infinity,
                width: '100%',
            });
            $("#selNotime").select2({
                multiple: false,
                allowClear: false,
                minimumResultsForSearch: Infinity,
                width: '100%',
            });
            $("#selEmailCheck").select2({
                multiple: false,
                allowClear: false,
                minimumResultsForSearch: Infinity,
                width: '100%',
            });
            $("#selOverlaps").select2({
                multiple: false,
                allowClear: false,
                minimumResultsForSearch: Infinity,
                width: '100%',
            });
            $("#selLeagueReportFmt").select2({
                multiple: true,
                maximumSelectionLength: 2,
                minimumResultsForSearch: Infinity,
                language: '{{ \Str::lower(app()->getLocale()) }}',
                allowClear: false,
                width: '100%',
            });
            $("#selLeagueReportFmt").val({{ collect($region->fmt_league_reports->getFlags())->pluck('value') }})
                .change();

            $("#selClubReportFmt").select2({
                multiple: true,
                maximumSelectionLength: 2,
                minimumResultsForSearch: Infinity,
                language: '{{ \Str::lower(app()->getLocale()) }}',
                allowClear: false,
                width: '100%',
            });
            $("#selClubReportFmt").val({{ collect($region->fmt_club_reports->getFlags())->pluck('value') }})
                .change();
        });
    </script>

@endpush
