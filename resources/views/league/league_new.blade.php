@extends('layouts.page')

@section('plugins.ICheck', true)
@section('plugins.Select2', true)

@section('content')
<x-card-form cardTitle="{{ __('league.title.new', ['region'=>session('cur_region')->code ]) }}" formAction="{{ route('league.store', app()->getLocale()) }}">
    <input type="hidden" class="form-control id=" region_id" name="region_id"
        value="{{ session('cur_region')->id }}">
    <div class="form-group row">
        <label for="shortname" class="col-sm-4 col-form-label">@lang('league.shortname')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('shortname') is-invalid @enderror"
                id="shortname" name="shortname" placeholder="@lang('league.shortname')"
                value="{{ old('shortname') }}">
            @error('shortname')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="name" class="col-sm-4 col-form-label">@lang('league.name')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                name="name" placeholder="@lang('league.name')" value="{{ old('name') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row ">
        <label for='selSize' class="col-sm-4 col-form-label">@lang('schedule.size')</label>
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <select class='js-selSize js-states form-control select2 @error('league_size_id') is-invalid @enderror id='selSize' name="league_size_id">
                    @if (old('league_size_id')!== null) 
                    <option value="{{ old('league_size_id') }}" selected >{{  App\Models\LeagueSize::find(old('league_size_id'))->description }}</option>
                    @endif                                        
                </select>
                @error('league_size_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="selSchedule"
            class="col-sm-4 col-form-label">{{ trans_choice('schedule.schedule', 1) }}</label>
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <select class='js-sel-schedule js-states form-control select2 @error('schedule_id')
                    is-invalid @enderror id='selSchedule' name='schedule_id'>
                    @if (old('schedule_id')!== null) 
                    <option value="{{ old('schedule_id') }}" selected >{{  App\Models\Schedule::find(old('schedule_id'))->name }}</option>
                    @endif
                    </select>
                @error('schedule_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="selAgeType" class="col-sm-4 col-form-label">@lang('league.agetype')</label>
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <select class='js-placeholder-single js-states form-control select2 @error('
                    age_type') is-invalid @enderror' id='selAgeType' name='age_type'>
                    @foreach ($agetype as $at)
                        <option value="{{ $at->value }}">{{ $at->description }}</option>
                    @endforeach
                </select>
                @error('age_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="selGenderType"
            class="col-sm-4 col-form-label">@lang('league.gendertype')</label>
        <div class="col-sm-6">
            <div class="input-group mb-3">
                <select class='js-placeholder-single js-states form-control select2 @error('
                    gender_type') is-invalid @enderror' id='selGenderType' name='gender_type'>
                    @foreach ($gendertype as $gt)
                        <option value="{{ $gt->value }}">{{ $gt->description }}</option>
                    @endforeach
                </select>
                @error('gender_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <div class="col-sm-4">
        </div>
        <div class="col-sm-6">
            <div class="form-group  clearfix">
                <div class="icheck-info d-inline">
                    <input type="checkbox" id="above_region" name="above_region">
                    <label for="above_region">@lang('league.above-region')</label>
                </div>
            </div>
        </div>
    </div>
</x-card-slot>
@endsection

@push('js')
    <script>
        $(function() {
            $('#frmClose').click(function(e){
                history.back();
            });

            $("#selAgeType").select2({
                theme: 'bootstrap4',
                multiple: false,
                allowClear: false,
                minimumResultsForSearch: -1,
            });
            $("#selGenderType").select2({
                theme: 'bootstrap4',
                multiple: false,
                allowClear: false,
                minimumResultsForSearch: -1,
            });

            $(".js-selSize").select2({
                placeholder: "@lang('schedule.action.size.select')...",
                theme: 'bootstrap4',
                allowClear: false,
                minimumResultsForSearch: 5,
                minimumInputLength: 3,
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

            $(".js-sel-schedule").select2({
                placeholder: "pls selec size first",
                theme: 'bootstrap4',
                multiple: false,
                allowClear: false,
                minimumResultsForSearch: -1
            });

            $(".js-selSize").on("change", function() {
                var selected = $(".js-selSize").val();
                console.log(selected);
                var url = "{{ route('schedule.sb.region_size', ['region' => session('cur_region')->id, 'size'=>':size:' ]) }}";
                url = url.replace(':size:', selected);
                
                $(".js-sel-schedule").val(null).trigger('change');
                $(".js-sel-schedule").select2({
                    theme: 'bootstrap4',
                    multiple: false,
                    allowClear: false,
                    minimumResultsForSearch: 5,
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

            });


        });
    </script>
@endpush
