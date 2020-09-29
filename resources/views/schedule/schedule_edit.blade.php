@extends('page')

@section('css')
<!-- Bootstrap Color Picker -->
<link href="{{ URL::asset('vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
  <!-- iCheck for checkboxes and radio inputs -->
<link href="{{ URL::asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
@endsection

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
                          <label for="region_id" class="col-sm-4 col-form-label">@lang('club.region')</label>
                          <div class="col-sm-6">
                              <input type="text" class="form-control" readonly id="region_id" name="region_id" placeholder="@lang('club.region')" value="{{ $schedule->region_id}}">
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
                        <div class="form-group row ">
                          <div class="icheck-info">
                            <input type="checkbox" id="active" name="active"
                            @if ($schedule->active) checked @endif>
                            <label for="active">{{__('Active')}} ?</label>
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
<!-- bootstrap color picker -->
<script src="{{ URL::asset('vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>

<script>
  $(function() {
      $('#cp2').colorpicker();
  });
</script>
@endsection
