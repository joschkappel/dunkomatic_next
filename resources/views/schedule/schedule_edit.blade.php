@extends('adminlte::page')

@section('css')
<!-- Bootstrap Color Picker -->
<link href="{{ URL::asset('vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Modify schedule {{ $schedule->name}} </h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('schedule.update',['schedule' => $schedule]) }}" method="POST">
                <div class="card-body">
                  <input type="hidden" name="_method" value="PUT">
                  @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Please fix the following errors
                        </div>
                        @endif
                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ $schedule->name }}">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                          <label for="region_id" class="col-sm-2 col-form-label">Region</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" readonly id="region_id" name="region_id" placeholder="region" value="{{ $schedule->region_id}}">
                            </div>
                        </div>
                        <div class="form-group row ">
                              <label for="eventcolor" class="col-sm-2 col-form-label">Color</label>
                              <div class="col-sm-10">
                                <div id="cp2" class="input-group">
                                  <input type="text" class="form-control input-lg @error('eventcolor') is-invalid @enderror" id="eventcolor" name="eventcolor" placeholder="Color" value="{{ $schedule->eventcolor}}">
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
                            <label for="active" class="col-sm-2 col-form-label">Active ?</label>
                            <div class="form-check col-sm-10">
                              {{ Form::hidden('active', 0) }}
                              {{ Form::checkbox('active', '1', $schedule->active) }}
                            </div>
                        </div>
                </div>
                <div class="card-footer">
                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                            <a class="btn btn-outline-dark " href="{{url()->previous()}}">Cancel</a>
                            <button type="submit" class="btn btn-info">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- right column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Delete schedule {{ $schedule->name}}</h3>
                </div>
                <!-- /.card-header -->
                {{ Form::model($schedule, array('route' => array('schedule.destroy', $schedule), 'method' => 'DELETE' )) }}
                    <div class="card-body">
                      This will delete the schedule including all calendar events !!
                    </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <a class="btn btn-outline-dark" href="{{url()->previous()}}">Cancel</a>
                                <button type="submit" class="btn btn-danger pull-right">Submit</button>
                            </div>
                        </div>
                  {{ Form::close() }}

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
