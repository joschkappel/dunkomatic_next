@extends('layouts.page')

@section('content')
<x-card-form cardTitle="{{ __('message.title.contact') }}" formAction="{{ route('contact.feedback') }}">
                        <div class="form-group row">
                            <label for="title" class="col-sm-2 col-form-label">@lang('message.title')</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="@lang('message.title')" value="{{ old('title') }}">
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="body" class="col-sm-2 col-form-label">@lang('message.body')</label>
                            <div class="col-sm-10">
                              <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="body" rows="20" id="summernote"></textarea>
                              @error('body')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
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

      });

 </script>

@endpush
