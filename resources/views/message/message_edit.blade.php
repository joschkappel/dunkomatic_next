@extends('layouts.page')

@section('content')
<x-card-form cardTitle="{{ __('message.title.edit') }}" formAction="{{ route('message.update',['message'=>$message['message']]) }}" formMethod="PUT">
    <div class="form-group row">
        <label for="title" class="col-sm-2 col-form-label">@lang('message.title')</label>
        <div class="col-sm-10">
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="@lang('message.title')" value="{{ (old('title')!='') ? old('title') : $message['message']->title }}">
            @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="greeting" class="col-sm-2 col-form-label">@lang('message.greeting')</label>
        <div class="col-sm-10">
            <input type="text" class="form-control @error('greeting') is-invalid @enderror" name="greeting" id="greeting"></input>
            @error('greeting')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="body" class="col-sm-2 col-form-label">@lang('message.body')</label>
        <div class="col-sm-10">
            <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="summernote"></textarea>
            @error('body')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="salutation" class="col-sm-2 col-form-label">@lang('message.salutation')</label>
        <div class="col-sm-10">
            <input type="text" class="form-control @error('salutation') is-invalid @enderror" name="salutation" id="salutation" ></input>
            @error('salutation')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="send_at" class="col-sm-2 col-form-label">@lang('message.send_at')</label>
        <div class="col-sm-10">
            <div class="input-group date" id="send_at" data-target-input="nearest">
                <input type="text" name='send_at' id='send_at' class="form-control datetimepicker-input @error('send_at') is-invalid @enderror" data-target="#send_at" />
                <div class="input-group-append" data-target="#send_at" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
                @error('send_at')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="selDestTo" class="col-sm-2 col-form-label">@lang('message.dest_to')</label>
        <div class="col-sm-10">
        <div class="input-group mb-3">
            <select class='js-sel-to js-states form-control select2 @error("dest_to") is-invalid @enderror' id='selDestTo' name="dest_to[]">
                @foreach ( $scopetype as $st )
                <option value="{{ $st->value }}">{{ $st->description }}</option>
                @endforeach
            </select>
            @error("dest_to")
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="selDestCc" class="col-sm-2 col-form-label">@lang('message.dest_cc')</label>
        <div class="col-sm-10">
        <div class="input-group mb-3">
            <select class='js-sel-cc js-states form-control select2 @error("dest_cc") is-invalid @enderror' id='selDestCc' name="dest_cc[]">
                @foreach ( $scopetype as $st )
                <option value="{{ $st->value }}">{{ $st->description }}</option>
                @endforeach
            </select>
            @error("dest_cc")
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
        </div>
    </div>
</x-card-form>
@endsection

@push('js')

  <script>
      $(function() {
        $('#frmClose').click(function(e){
            history.back();
        });
        $('#summernote').summernote({
          lang: @if (app()->getLocale() == 'de') 'de-DE' @else 'en-US'  @endif,
          placeholder: '{{ __('message.body.enter') }}',
          tabsize: 2,
          height: 100,
          toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear', 'italic']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['view', ['fullscreen', 'help']],
          ],
        });

        var content = '{!! (old('body')!='') ? old('body') : $message['message']->body !!}';
        $('#summernote').summernote('code',content);

        $("#greeting").val('{{ (old('greeting')!='') ? old('greeting') : $message['message']->greeting }}');
        $("#salutation").val('{{ (old('salutation')!='') ? old('salutation') : $message['message']->salutation }}');
        $("#selDestTo").select2({
            width: '100%',
            multiple: true,
            allowClear: false,
        });
        $("#selDestTo").val({{ json_encode(Arr::flatten($message['dest_to'])) }} ).change();
        $("#selDestCc").select2({
            width: '100%',
            multiple: true,
            allowClear: false,
        });
        $("#selDestCc").val({{ json_encode(Arr::flatten($message['dest_cc'])) }} ).change();


        moment.locale('{{ app()->getLocale() }}');

        $('#send_at').datetimepicker({
            format: 'L',
            locale: '{{ app()->getLocale()}}',
            defaultDate: moment('{{ $message['message']->send_at }}'),
            // minDate: moment().add(1, 'd')
        });

      });

 </script>

@endpush
