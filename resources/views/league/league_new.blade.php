
@extends('layouts.page')

@section('plugins.ICheck',true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">

                <!-- general form elements -->
                <div class="card card-info">
                  <div class="card-header">
                      <h3 class="card-title">@lang('league.title.new', ['region'=>Auth::user()->region ])</h3>
                  </div>
                  <!-- /.card-header -->
                    <form class="form-horizontal" action="{{ route('league.store', app()->getLocale()) }}" method="post">
                        <div class="card-body">
                            @csrf
                            @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                @lang('Please fix the following errors')
                            </div>
                            @endif
                            <div class="form-group row">
                                <label for="region" class="col-sm-4 col-form-label">@lang('club.region')</label>
                                <div class="col-sm-6">
                                    <input type="text" readonly class="form-control @error('region') is-invalid @enderror" id="region" name="region" placeholder="@lang('club.region')" value="{{Auth::user()->region}}">
                                    @error('region')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="shortname" class="col-sm-4 col-form-label">@lang('league.shortname')</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @error('shortname') is-invalid @enderror" id="shortname" name="shortname" placeholder="@lang('league.shortname')" value="{{ old('shortname') }}">
                                    @error('shortname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">@lang('league.name')</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="@lang('league.name')" value="{{ old('name') }}">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="selSchedule" class="col-sm-4 col-form-label">{{ trans_choice('schedule.schedule',1)}}</label>
                                <div class="col-sm-6">
                                  <select class='js-sel-schedule js-states form-control select2 @error('schedule_id') is-invalid @enderror' id='selSchedule' name='schedule_id'></select>
                                  @error('schedule_id')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="selAgeType" class="col-sm-4 col-form-label">@lang('league.agetype')</label>
                                <div class="col-sm-6">
                                  <select class='js-placeholder-single js-states form-control select2 @error('age_type') is-invalid @enderror' id='selAgeType' name='age_type'>
                                     @foreach ( $agetype as $at )
                                       <option value="{{ $at->value }}" >{{ $at->description }}</option>
                                     @endforeach
                                  </select>
                                  @error('age_type')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="selGenderType" class="col-sm-4 col-form-label">@lang('league.gendertype')</label>
                                <div class="col-sm-6">
                                  <select class='js-placeholder-single js-states form-control select2 @error('gender_type') is-invalid @enderror' id='selGenderType' name='gender_type'>
                                     @foreach ( $gendertype as $gt )
                                       <option value="{{ $gt->value }}" >{{ $gt->description }}</option>
                                     @endforeach
                                  </select>
                                  @error('gender_type')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                                </div>
                            </div>
                            <div class="form-group  clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" id="above_region" name="above_region" >
                                <label for="above_region" >@lang('league.above-region')</label>
                              </div>
                            </div>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" id="active" name="active" checked>
                                <label for="active">{{ __('Active')}} ?</label>
                              </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
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

      $("#selAgeType").select2({
          theme: 'bootstrap4',
          multiple: false,
          allowClear: false,
          minimumResultsForSearch: 10,
      });
      $("#selGenderType").select2({
          theme: 'bootstrap4',
          multiple: false,
          allowClear: false,
          minimumResultsForSearch: 10,
      });


      $(".js-sel-schedule").select2({
          placeholder: "@lang('schedule.action.select')...",
          theme: 'bootstrap4',
          multiple: false,
          allowClear: true,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('schedule.sb.region')}}",
                  type: "get",
                  delay: 250,
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
      });


      });
</script>
@endpush
