@extends('layouts.page')

@section('content')
<x-card-form colWidth=10 cardTitle="{{ $cardTitle }}" formAction="{{ $uploadRoute }}" :isMultipart="true">
    @if ( Session::has('status') )
        <div class="alert alert-success" role="alert">
            @lang('game.import.success')
        </div>
    @endif
    <div class="form-group row">
        <div class="col-md-12">
            <div class="file-loading">
                <input id="gfile" name="gfile" class="file" type="file" data-theme="fas" accept=".xlsx,.csv,.ods,application/msexcel">
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
            <div class="text-danger">{!! $message !!}</div>
        @endforeach
        </div>
    </div>
    @endif

    <x-slot name="addButtons">
        <button type="button" class="btn btn-secondary mr-2" id="frmReset">{{ __('Reset')}}</button>
    </x-slot>
    <div class="alert alert-info" role="alert">
        <h4 class="alert-heading">{{__('import.alert.header')}}</h4>
        <p>{{ __('import.'.$context.'.uploadhint.1')}}</p>
        <p>{{ __('import.'.$context.'.uploadhint.2')}}</p>
        <p>{{ __('import.'.$context.'.uploadhint.3')}}</p>
        <hr>
        <p class="mb-0">{{ __('import.alert.footer')}}</p>
      </div>
</x-card-form>
@endsection

@section('js')

    <script>
        $('#gfile').fileinput({
            theme: 'fas',
            language: '{{ app()->getLocale() }}',
            showUpload: false,
            hideThumbnailContent: false,
            showPreview: false,
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
