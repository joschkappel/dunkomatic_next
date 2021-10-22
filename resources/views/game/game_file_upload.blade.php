@extends('layouts.page')

@section('plugins.FileUpload', true)

@section('content')
<x-card-form cardTitle="{{ $cardTitle }}" formAction="{{ $uploadRoute }}" :isMultipart="true">
    @if ( Session::has('status') )
        <div class="alert alert-success" role="alert">
            @lang('game.import.success')
        </div>
    @endif
    <div class="form-group row">
        <div class="col-md-12">
            <div class="file-loading">
                <input id="gfile" name="gfile" class="file" type="file" data-theme="fas" accept=".xlsx,.csv,.tsv,.ods,.xls,application/msexcel">
            </div>
        </div>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        @lang('game.import.failure')
    </div>

    <div class="form-group row">
        <div class="col-sm-10">
        @foreach ($errors->all() as $message)
            <div class="text-danger">{{ $message }}</div>
        @endforeach
        </div>
    </div>
    @endif

    <x-slot name="addButtons">
        <button type="button" class="btn btn-secondary mr-2" id="frmReset">{{ __('Reset')}}</button>
    </x-slot>
</x-card-form>
@endsection

@section('js')

    @if ( app()->getLocale() == "de"){
    <script src="{{ URL::asset('vendor/kartik-v/bootstrap-fileinput/js/locales/de.js') }}"></script>
    @endif

    <script>
        $('#gfile').fileinput({
            theme: 'fas',
            language: '{{ app()->getLocale() }}',
            showUpload: false,
            hideThumbnailContent: false,
            showPreview: false,
            showUpload: false,
        });

        $(document).ready(function(){


            $('#frmClose').click(function(e){
                history.back();
            });
            $('#frmReset').click(function(e){
                {{-- $('#cardForm')[0].reset(); --}}
                location.reload();
            });


        });
    </script>
@endsection
