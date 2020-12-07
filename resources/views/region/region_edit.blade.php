@extends('layouts.page')

@section('plugins.Slider',true)

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
                <form class="form-horizontal" action="{{ route('region.update',['region'=>$region]) }}" method="post">
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="code" class="col-sm-6 col-form-label">@lang('region.code')</label>
                            <div class="col-sm-4">
                                <input type="text"  readonly class="form-control" id="code" name="code" value="{{ (old('code')!='') ? old('code') : $region->code }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-6 col-form-label">@lang('region.name')</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="name" name="name" value="{{ (old('name')!='') ? old('name') : $region->name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selNolead" class="col-sm-6 col-form-label">@lang('region.job.noleads')</label>
                            <div class="col-sm-4">
                              <select class='js-sel-noleads js-states form-control select2' id='selNolead' name="job_noleads">
                                @foreach ($frequencytype as $ft)
                                    <option @if ($ft->value == $region->job_noleads) selected @endif value="{{ $ft->value }}">{{ $ft->description }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selEmailCheck" class="col-sm-6 col-form-label">@lang('region.job.emails')</label>
                            <div class="col-sm-4">
                              <select class='js-sel-emailcheck js-states form-control select2' id='selEmailCheck' name="job_email_valid">
                                @foreach ($frequencytype as $ft)
                                    <option @if ($ft->value == $region->job_email_valid) selected @endif value="{{ $ft->value }}">{{ $ft->description }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selNotime" class="col-sm-6 col-form-label">@lang('region.job.notime')</label>
                            <div class="col-sm-4">
                              <select class='js-sel-notime js-states form-control select2' id='selNotime' name="job_game_notime">
                                @foreach ($frequencytype as $ft)
                                    <option @if ($ft->value == $region->job_game_notime) selected @endif value="{{ $ft->value }}">{{ $ft->description }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selOverlaps" class="col-sm-6 col-form-label">@lang('region.job.overlaps')</label>
                            <div class="col-sm-4">
                              <select class='js-sel-overlaps js-states form-control select2' id='selOverlaps' name="job_game_overlaps">
                                @foreach ($frequencytype as $ft)
                                    <option @if ($ft->value == $region->job_game_overlaps) selected @endif value="{{ $ft->value }}">{{ $ft->description }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="game_slot" class="col-sm-6 col-form-label">@lang('region.game_slot')</label>
                            <div class="col-sm-4">
                              <input id="game_slot" name="game_slot" type="text" class="form-control" ></input>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selLeagueReport" class="col-sm-6 col-form-label">@lang('region.job.league_reports')</label>
                            <div class="col-sm-4">
                              <select class='js-sel-league-reports js-states form-control select2' id='selLeagueReport' name="job_league_reports">
                                @foreach ($frequencytype as $ft)
                                    <option @if ($ft->value == $region->job_league_reports) selected @endif value="{{ $ft->value }}">{{ $ft->description }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                           <label for="selLeagueReportFmt" class="col-sm-6 col-form-label"></label>
                            <div class="col-sm-4">
                              <select class='js-sel-league-reports-fmt js-states form-control select2' id='selLeagueReportFmt' name="fmt_league_reports[]">
                                @foreach ($filetype as $ft)
                                    <option value="{{ $ft->value }}">{{ $ft->description }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selClubReport" class="col-sm-6 col-form-label">@lang('region.job.club_reports')</label>
                            <div class="col-sm-4">
                              <select class='js-sel-league-reports js-states form-control select2' id='selClubReport' name="job_club_reports">
                                @foreach ($frequencytype as $ft)
                                    <option @if ($ft->value == $region->job_club_reports) selected @endif value="{{ $ft->value }}">{{ $ft->description }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="form-group row">
                           <label for="selClubReportFmt" class="col-sm-6 col-form-label"></label>
                            <div class="col-sm-4">
                              <select class='js-sel-club-reports-fmt js-states form-control select2' id='selClubReportFmt' name="fmt_club_reports[]">
                                @foreach ($filetype as $ft)
                                    <option value="{{ $ft->value }}">{{ $ft->description }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                    <div class="card-footer">
                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
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
        var gameslotSlider = $("#game_slot").bootstrapSlider({
          ticks: [60,75,90,105,120,135,150],
          ticks_labels: ['60','75', '90','105', '120', '135','150'],
          lock_to_ticks: true
        });

        gameslotSlider.bootstrapSlider('setValue', {{ (old('game_slot')!='') ? old('game_slot') : $region->game_slot }});

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
            language: '{{ \Str::lower( app()->getLocale()) }}',
            allowClear: false,
        });
        $("#selLeagueReportFmt").val({{ collect($region->fmt_league_reports->getFlags())->pluck('value')  }} ).change();

        $("#selClubReport").select2({
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
        });
        $("#selClubReportFmt").select2({
            theme: 'bootstrap4',
            multiple: true,
            maximumSelectionLength: 2,
            language: '{{ \Str::lower( app()->getLocale()) }}',
            allowClear: false,
        });
        $("#selClubReportFmt").val({{ collect($region->fmt_club_reports->getFlags())->pluck('value')  }} ).change();
      });

 </script>

@endpush
