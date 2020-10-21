@extends('layouts.page')

@section('plugins.Summernote', true)
@section('plugins.Moment', true)
@section('plugins.TempusDominus', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('message.title.new', ['region' => Auth::user()->region ])</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('message.store') }}" method="post">
                    <div class="card-body">
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif
                        <input type="hidden" class="form-control" id="author" name="author" value="{{ Auth::user()->id }}">
                        <input type="hidden" class="form-control" id="region_id" name="dest.region_id" value="{{ Auth::user()->region }}">
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 col-form-label">@lang('message.title')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="@lang('message.title')" value="{{ old('title') }}">
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="greeting" class="col-sm-4 col-form-label">@lang('message.greeting')</label>
                            <div class="col-sm-6">
                              <textarea class="form-control @error('greeting') is-invalid @enderror" name="greeting" id="greeting"></textarea>
                              @error('greeting')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="body" class="col-sm-4 col-form-label">@lang('message.body')</label>
                            <div class="col-sm-6">
                              <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="summernote"></textarea>
                              @error('body')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="salutation" class="col-sm-4 col-form-label">@lang('message.salutation')</label>
                            <div class="col-sm-6">
                              <textarea class="form-control @error('salutation') is-invalid @enderror" name="salutation" id="salutation"></textarea>
                              @error('salutation')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="send_at" class="col-sm-4 col-form-label">@lang('message.send_at')</label>
                            <div class="col-sm-6">
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
                            <label for="selDestTo" class="col-sm-4 col-form-label">@lang('message.dest_to')</label>
                            <div class="col-sm-6">
                              <select class='js-sel-to js-states form-control select2 @error("dest_to") is-invalid @enderror' id='selDestTo' name="dest_to[]">
                                 @foreach ( $scopetype as $st )
                                   <option value="{{ $st->value }}" >{{ $st->description }}</option>
                                 @endforeach
                              </select>
                              @error("dest_to")
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selDestCc" class="col-sm-4 col-form-label">@lang('message.dest_cc')</label>
                            <div class="col-sm-6">
                              <select class='js-sel-cc js-states form-control select2 @error("dest_cc") is-invalid @enderror' id='selDestCc' name="dest_cc[]">
                                 @foreach ( $scopetype as $st )
                                   <option value="{{ $st->value }}" >{{ $st->description }}</option>
                                 @endforeach
                              </select>
                              @error("dest_cc")
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
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
      $(function() {
        $('#summernote').summernote({
          lang: @if (app()->getLocale() == 'de') 'de-DE' @else 'en-US'  @endif,
          placeholder: 'Edit your message...',
          tabsize: 2,
          height: 100,
          toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear', 'italic']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']],
          ],
        });

        $("#selDestTo").select2({
            theme: 'bootstrap4',
            multiple: true,
            allowClear: false,
        });

        $("#selDestCc").select2({
            theme: 'bootstrap4',
            multiple: true,
            allowClear: false,
        });

        moment.locale('{{ app()->getLocale() }}');

        $('#send_at').datetimepicker({
            format: 'L',
            locale: '{{ app()->getLocale()}}',
            defaultDate: moment().add(1, 'd').format('L'),
            minDate: moment().add(1, 'd').format('L'),
        });

      });

 </script>

@endpush
