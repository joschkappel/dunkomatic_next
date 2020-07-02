@extends('adminlte::page')

@section('plugins.Select2', true)

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
                    <h3 class="card-title">Create a new schedule </h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('schedule.store') }}" method="POST">
                <div class="card-body">
                  @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Please fix the following errors
                        </div>
                        @endif
                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                          <label for="region_id" class="col-sm-2 col-form-label">Region</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" readonly id="region_id" name="region_id" placeholder="region" value="{{ $region  }}">
                            </div>
                        </div>
                        <div class="form-group row ">
                              <label for="eventcolor" class="col-sm-2 col-form-label">Color</label>
                              <div class="col-sm-10">
                                <div id="cp2" class="input-group">
                                  <input type="text" class="form-control input-lg @error('eventcolor') is-invalid @enderror" id="eventcolor" name="eventcolor" placeholder="Color" value="#DDEE00">
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
                              <label for='selSize' class="col-sm-2 col-form-label">League Size</label>
                              <div class="col-sm-10">
                                <select class='js-example-placeholder-single js-states form-control select2 @error('size') is-invalid @enderror' id='selSize' name="size"></select>
                                @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                              </div>
                        </div>
                        <div class="form-group row ">
                            <label for="active" class="col-sm-2 col-form-label">Active ?</label>
                            <div class="form-check col-sm-10">
                              {{ Form::hidden('active', 0) }}
                              {{ Form::checkbox('active', '1', '1') }}
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
    </div>
</div>
@endsection

@section('js')
<!-- bootstrap color picker -->
<script src="{{ URL::asset('vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>

<script>
  $(function() {
      $('#cp2').colorpicker();

      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4',
      });

      $(".js-example-placeholder-single").select2({
          placeholder: "Select a size...",
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

  });
</script>
@endsection
