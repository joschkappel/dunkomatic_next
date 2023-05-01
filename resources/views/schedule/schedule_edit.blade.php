@extends('layouts.page')

@section('content')
<x-card-form cardTitle="{{ __('schedule.title.modify', ['schedule' => $schedule->name] ) }}" formAction="{{ route('schedule.update', ['schedule' => $schedule]) }}" formMethod="PUT">
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
            <div class="form-group  clearfix d-flex align-items-center">
                <div class="icheck-info d-inline">
                    <input type="checkbox" id="custom_events" name="custom_events" @if ($schedule->custom_events) checked @endif value="1">
                    <label for="custom_events">@lang('schedule.custom_events')</label>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-6">
            <div class="form-group  clearfix d-flex align-items-center">
                <div class="icheck-info d-inline">
                    <input type="checkbox" id="active" name="active"  @if ($schedule->active) checked @endif value="1">
                    <label for="active">{{__('Active')}}</label>
                </div>
            </div>
        </div>

    </div>
    <div class="form-group row ">
        <label for='selSize' class="col-sm-4 col-form-label">@lang('schedule.size')</label>
        <div class="col-sm-6">
            @if ($schedule->events()->count() == 0)
            <div class="input-group mb-3">
                <select class='js-sizes js-states form-control select2 @error('league_size_id') is-invalid @enderror' id='selSize' name="league_size_id">
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
        <label for="iterationRange" class="col-sm-4 col-form-label">@lang('schedule.iterations')</label>
        <div class="col-sm-6">
            @if ($schedule->events()->count() == 0)
            <input id="iterationRange" type="text" name="iterations" value="">
            @else
                <span class='text-info'>@lang('schedule.edit.iterations')</span>
            @endif
        </div>
    </div>

    <div class="form-group row ">
        <label for="note_homegames" class="col-sm-4 col-form-label">@lang('schedule.note_homegames')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('note_homegames') is-invalid @enderror" id="note_homegames" name="note_homegames" value="{{ $schedule->note_homegames }}">
            @error('note_homegames')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row ">
        <label for="note_2" class="col-sm-4 col-form-label">@lang('schedule.note_2')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('note_2') is-invalid @enderror" id="note_2" name="note_2" value="{{ $schedule->note_2 }}">
            @error('note_2')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <x-slot name="addButtons">
        <button type="button" id="adrval" class="btn btn-secondary mr-2">{{ __('gym.action.validate_adr')}}</button>
    </x-slot>
</x-card-form>
@endsection

@section('js')

    <script>
        $(function() {
            $('#frmClose').click(function(e){
                history.back();
            });

            var custom_values_1 = [1,2,3];
            var custom_values_2 = [1];
            var values_p = ["{{ __('schedule.single') }}", "{{__('schedule.double')}}", "{{__('schedule.triple')}}"];

            @if ($schedule->custom_events)
            var values = custom_values_2;
            @else
            var values = custom_values_1;
            @endif

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

            $(".js-sizes").select2({
                placeholder: "@lang('schedule.action.size.select')...",
                width: '100%',
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
                $('#selSize').val(null).trigger('change')
                if ( $('#custom_events').prop('checked')){
                    $("#iterationRange").data("ionRangeSlider").update({ values: custom_values_2, });
                } else {
                    $("#iterationRange").data("ionRangeSlider").update({ values: custom_values_1, });
                }
            });


            @if (old('iterations') != '')
                $("#iterationRange").data("ionRangeSlider").update({ from: values.indexOf( {{old('iterations') }}) });
            @else
                $("#iterationRange").data("ionRangeSlider").update({ from: values.indexOf({{ $schedule->iterations }}) });
            @endif
        });
    </script>
@endsection
