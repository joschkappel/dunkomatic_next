@extends('layouts.page')

@section('plugins.Select2', true)
@section('plugins.ICheck', true)
@section('plugins.Colorpicker', true)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">@lang('schedule.title.modify', ['schedule' => $schedule->name] )</h3>
                    </div>
                    <!-- /.card-header -->
                    <form class="form-horizontal" action="{{ route('schedule.update', ['schedule' => $schedule]) }}"
                        method="POST">
                        <div class="card-body">
                            @method('PUT')
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    @lang('Please fix the following errors')
                                </div>
                            @endif
                            <div class="form-group row ">
                                <label for="title" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" placeholder="Name"
                                        value="{{ old('name') ? old('name') : $schedule->name }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row ">
                                <div class="col-sm-4">
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group  clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="custom_events" name="custom_events" @if ($schedule->custom_events) checked @endif>
                                            <label for="custom_events">@lang('schedule.custom_events')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for='selSize' class="col-sm-4 col-form-label">@lang('schedule.size')</label>
                                <div class="col-sm-6">
                                    @if ($schedule->events()->count() == 0)
                                    <div class="input-group mb-3">
                                        <select class='js-sizes js-states form-control select2 @error(' league_size_id')
                                            is-invalid @enderror' id='selSize' name="league_size_id">
                                            <option
                                                value="{{ old('league_size_id') ? old('league_size_id') : $schedule->league_size_id }}"
                                                selected="selected">
                                                {{ old('league_size_id') ? App\Models\LeagueSize::find(old('league_size_id'))->description : $schedule->league_size['description'] }}
                                            </option>
                                        </select>
                                        @error('league_size_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        </div>
                                    @else
                                        <span class='text-info'>@lang('schedule.edit.size')</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="eventcolor" class="col-sm-4 col-form-label">@lang('schedule.color')</label>
                                <div class="col-sm-6">
                                    <div id="cp2" class="input-group">
                                        <input type="text"
                                            class="form-control input-lg @error('eventcolor') is-invalid @enderror"
                                            id="eventcolor" name="eventcolor" placeholder="@lang('schedule.color')"
                                            value="{{ old('eventcolor') ? old('eventcolor') : $schedule->eventcolor }}">
                                        <span class="input-group-append">
                                            <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                        </span>
                                    </div>
                                    @error('eventcolor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <!-- /.input group -->
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar"
                                aria-label="Toolbar with button groups">
                                <a class="btn btn-outline-dark " href="{{ route('schedule.index', ['language' => app()->getLocale()]) }}">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
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
            $('#cp2').colorpicker();
            $(".js-sizes").select2({
                placeholder: "@lang('schedule.action.size.select')...",
                theme: 'bootstrap4',
                allowClear: false,
                minimumResultsForSearch: -1,
                ajax: {
                    url: "{{ url('size/index') }}",
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

            $('#selSize').on('select2:select', function(e) {
                var data = e.params.data;
                // console.log(data);
                if (data.id == {{ App\Models\LeagueSize::UNDEFINED }}) {
                    // console.log('UNDEFIND');
                    $('#custom_events').prop("checked", true);
                } else {
                    $('#custom_events').prop("checked", false);
                }
            });
            $('#custom_events').on('change', function(event) {
                $('#selSize').val(null).trigger('change')
            });
        });
    </script>
@endsection
