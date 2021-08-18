@extends('layouts.page')

@section('plugins.Select2', true)
@section('plugins.ICheck', true)
@section('plugins.Colorpicker', true)
@section('plugins.RangeSlider',true)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-secondary">
                    <div class="card-header container-fluid" id="schedulenew"">
                                    <h3 class=" card-title">@lang('schedule.title.new') </h3>
                    </div>
                    <!-- /.card-header -->
                    <form class="form-horizontal" action="{{ route('schedule.store') }}" method="POST">

                        <div class="card-body">
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
                                        name="name" placeholder="Name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" class="form-control" readonly id="region_id" name="region_id"
                                value="{{ session('cur_region')->id }}">
                            <div class="form-group row ">
                                <label for="eventcolor" class="col-sm-4 col-form-label">@lang('schedule.color')</label>
                                <div class="col-sm-6">
                                    <div id="cp2" class="input-group">
                                        <input type="text"
                                            class="form-control input-lg @error('eventcolor') is-invalid @enderror"
                                            id="eventcolor" name="eventcolor" placeholder="@lang('schedule.color')"
                                            value="{{ old('eventcolor') ?? '#DDEE00' }}">
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
                            <div class="form-group row ">
                                <div class="col-sm-4">
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group  clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="custom_events" name="custom_events">
                                            <label for="custom_events">@lang('schedule.custom_events')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for='selSize' class="col-sm-4 col-form-label">@lang('schedule.size')</label>
                                <div class="col-sm-6">
                                 <div class="input-group mb-3">
                                    <select class='js-selSize js-states form-control select2 @error(' league_size_id')
                                        is-invalid @enderror' id='selSize' name="league_size_id"></select>
                                    @error('league_size_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="iterationRange" class="col-sm-4 col-form-label">@lang('schedule.iterations')</label>
                                <div class="col-sm-6">
                                    <input id="iterationRange" type="text" name="iterations" value="">
                                </div>
                            </div>                            
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar"
                                aria-label="Toolbar with button groups">
                                <a class="btn btn-outline-dark "
                                    href="{{ route('schedule.index', ['language' => app()->getLocale()]) }}">{{ __('Cancel') }}</a>
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
    <!-- bootstrap color picker -->
    <script src="{{ URL::asset('vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>

    <script>
        $(function() {
            var custom_values_1 = [1,2,3];
            var custom_values_2 = [1];
            var values_p = ["{{ __('schedule.single') }}", "{{__('schedule.double')}}", "{{__('schedule.triple')}}"];

            var values = custom_values_1; 

            $('#iterationRange').ionRangeSlider({
                skin: "big",
                grid    : false,
                step    :1 ,
                postfix: '-'+'{{ trans_choice('league.league',1 ) }}',
                values: values,
                prettify: function (n) {
                    var ind = custom_values_1.indexOf(n);
                    return values_p[ind];
                },                
            });



            $('#cp2').colorpicker();
            $(".js-selSize").select2({
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
                    $("#iterationRange").data("ionRangeSlider").update({ values: custom_values_2, });
                } else {
                    $('#custom_events').prop("checked", false);
                    $("#iterationRange").data("ionRangeSlider").update({ values: custom_values_1, });
                }
            });
            $('#custom_events').on('change', function(event) {
                if (this.checked) {
                    //console.log('CHECKED');
                    $('#selSize').val(null).trigger('change')
                    if ( $('#custom_events').prop('checked')){
                        $("#iterationRange").data("ionRangeSlider").update({ values: custom_values_2, });
                    } else {
                        $("#iterationRange").data("ionRangeSlider").update({ values: custom_values_1, });
                    }

                }
            });

            @if (old('iterations') != '')
                $("#iterationRange").data("ionRangeSlider").update({ from: values.indexOf( {{old('iterations') }}) });
            @endif            

        });
    </script>
@endsection
