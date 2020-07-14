
@extends('adminlte::page')

@push('css')
  <!-- iCheck for checkboxes and radio inputs -->
<link href="{{ URL::asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">

                <!-- general form elements -->
                <div class="card card-info">
                  <div class="card-header">
                      <h3 class="card-title">Create a new league in region {{ Auth::user()->region }}</h3>
                  </div>
                  <!-- /.card-header -->
                    <form class="form-horizontal" action="{{ route('league.store') }}" method="post">
                        <div class="card-body">
                            @csrf
                            @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                Please fix the following errors
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="region" class="col-sm-2 col-form-label">Region</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control @error('region') is-invalid @enderror" id="region" name="region" placeholder="region" value="HBVDA">
                                    @error('region')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="shortname" class="col-sm-2 col-form-label">Shortname</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('shortname') is-invalid @enderror" id="shortname" name="shortname" placeholder="Shprtname" value="{{ old('shortname') }}">
                                    @error('shortname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ old('name') }}">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="selSchedule" class="col-sm-2 col-form-label">Schedule</label>
                                <div class="col-sm-10">
                                  <select class='js-example-placeholder-single js-states form-control select2' id='selSchedule' name='schedule_id'></select>
                                </div>
                            </div>
                            <div class="form-group ">
                              <div class="icheck-info col-sm-10">
                                <input type="checkbox" id="above_region" name="above_region" >
                                <label for="above_region" >Above Region ?</label>
                              </div>
                            </div>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" id="active" name="active" checked>
                                <label for="active">Active ?</label>
                              </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-primary">Submit</button>
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


      $(".js-example-placeholder-single").select2({
          placeholder: "Select a schedule...",
          multiple: false,
          allowClear: true,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('schedule.list_sel')}}",
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
