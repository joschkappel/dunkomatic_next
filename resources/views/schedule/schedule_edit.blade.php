@extends('layouts.page')

@section('plugins.Select2', true)
@section('plugins.ICheck',true)
@section('plugins.Colorpicker',true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('schedule.title.modify', ['schedule' => $schedule->name] )</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('schedule.update',['schedule' => $schedule]) }}" method="POST">
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
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ $schedule->name }}">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for='selSize' class="col-sm-4 col-form-label">@lang('schedule.size')</label>
                            <div class="col-sm-6">
                                @if ($schedule->events()->count() == 0)
                                <select class='js-sizes js-states form-control select2 @error('size') is-invalid @enderror' id='selSize' name="league_size_id">
                                @if ( $schedule->league_size_id )
                                <option value="{{ $schedule->league_size_id }}" selected="selected">{{ $schedule->league_size['description'] }}</option>
                                @endif
                                </select>
                                @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @else
                                <span class='text-info'>@lang('schedule.edit.size')</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row ">
                              <label for="eventcolor" class="col-sm-4 col-form-label">@lang('schedule.color')</label>
                              <div class="col-sm-6">
                                <div id="cp2" class="input-group">
                                  <input type="text" class="form-control input-lg @error('eventcolor') is-invalid @enderror" id="eventcolor" name="eventcolor" placeholder="@lang('schedule.color')" value="{{ $schedule->eventcolor}}">
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
                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                            <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
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
  });
  $(".js-sizes").select2({
          placeholder: "@lang('schedule.action.size.select')...",
          theme: 'bootstrap4',
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ url('size/index')}}",
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
</script>
@endsection
