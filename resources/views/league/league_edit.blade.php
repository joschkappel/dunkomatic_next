
@extends('layouts.page')

@section('plugins.ICheck', true)


@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-info">
                  <div class="card-header">
                      <h3 class="card-title">@lang('league.title.edit', ['league'=>$league->shortname ])</h3>
                  </div>
                  <!-- /.card-header -->
                  <form class="form-horizontal" action="{{ route('league.update',['language'=>app()->getLocale(), 'league' => $league]) }}" method="post">
                        <div class="card-body">
                            @csrf
                            @method('PUT')
                            @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                @lang('Please fix the following errors')
                            </div>
                            @endif
                            <div class="form-group row">
                                <label for="shortname" class="col-sm-4 col-form-label">@lang('league.shortname')</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @error('shortname') is-invalid @enderror" id="shortname" name="shortname" placeholder="@lang('league.shortname')" value="{{ (old('shortname')!='') ? old('shortname') : $league->shortname }}">
                                    @error('shortname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">@lang('league.name')</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="@lang('league.name')" value="{{  (old('name')!='') ? old('name') : $league->name }}">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="selSchedule" class="col-sm-4 col-form-label">{{ trans_choice('league.schedule',1)}}</label>
                                <div class="col-sm-6">
                                  <select class='js-example-placeholder-single js-states form-control select2' id='selSchedule' name='schedule_id'>
                                  @if ( $league->schedule_id )
                                     <option value="{{ $league->schedule_id }}" selected="selected">{{ $league->schedule['name'] }}</option>
                                  @endif
                                  </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="selAgeType" class="col-sm-4 col-form-label">@lang('league.agetype')</label>
                                <div class="col-sm-6">
                                  <select class='js-placeholder-single js-states form-control select2 @error('age_type') is-invalid @enderror' id='selAgeType' name='age_type'>
                                     @foreach ( $agetype as $at )
                                       <option value="{{ $at->value }}" @if ( $league->age_type == $at->value ) selected="selected" @endif >{{ $at->description }}</option>
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
                                       <option value="{{ $gt->value }}" @if ( $league->gender_type == $gt->value ) selected="selected" @endif>{{ $gt->description }}</option>
                                     @endforeach
                                  </select>
                                  @error('gender_type')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                                </div>
                            </div>
                            <div class="form-group  row">
                              <div class="icheck-info ">
                                <input type="checkbox" id="above_region" name="above_region"
                                @if ($league->above_region) checked @endif>
                                <label for="above_region" >@lang('league.above-region') ?</label>
                              </div>
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

      $("#selAgeType").select2({
          theme: 'bootstrap4',
          multiple: false,
          allowClear: false,
          minimumResultsForSearch: 10
      });
      $("#selGenderType").select2({
          theme: 'bootstrap4',
          multiple: false,
          allowClear: false,
          minimumResultsForSearch: 10
      });


      $("#selSchedule").select2({
          placeholder: "Select a schedule...",
          theme: 'bootstrap4',
          multiple: false,
          allowClear: true,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('schedule.sb.region', ['region' => $league->region_id])}}",
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
