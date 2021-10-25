@extends('layouts.page')

@section('plugins.Select2', true)

@section('content')
<x-card-form cardTitle="{{ __('region.title.create') }}" formAction="{{ route('region.store') }}">
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
            <div class="input-group mb-3">
                <select class='sel-region js-states form-control select2' id='selRegion' name='region_id'>
                </select>
            </div>
            </div>
    </div>
</x-card-form>
@endsection

@push('js')

  <script>
        $(document).ready(function(){
            $('#frmClose').click(function(e){
                history.back();
            });

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
