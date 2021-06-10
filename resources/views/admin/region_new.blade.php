@extends('layouts.page')

@section('plugins.Select2', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header bg-secondary">
                    <h3 class="card-title">@lang('region.title.create')</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('region.store') }}" method="POST">
                    <div class="card-body">
                        @csrf
                        @method('POST')
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="code" class="col-sm-6 col-form-label">@lang('region.code')</label>
                            <div class="col-sm-4">
                                <input type="text"  class="form-control" id="code" name="code" value="{{ (old('code')!='') ? old('code') : '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-6 col-form-label">@lang('region.name')</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="name" name="name" value="{{ (old('name')!='') ? old('name') : ''}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selRegion" class="col-sm-6 col-form-label">@lang('region.hq')</label>
                                <div class="col-sm-4">
                                    <select class='sel-region js-states form-control select2' id='selRegion' name='region_id'>
                                    </select>
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
        $(document).ready(function(){

            $("#selRegion").select2({
                multiple: false,
                theme: 'bootstrap4',
                allowClear: true,
                placeholder: "{{__('club.region')}}",
                ajax: {
                    url: "{{ route('region.hq.sb')}}",
                    type: "get",
                    delay: 250,
                    processResults: function (response) {
                        return { results: response };
                        },
                    cache: true
                    }
            });
        });
 </script>

@endpush
