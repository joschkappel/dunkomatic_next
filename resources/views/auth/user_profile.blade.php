@extends('layouts.page')

@section('plugins.Select2', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('auth.title.edit')</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form class="form-horizontal" action="{{ route('admin.user.update', ['user' => Auth::user()]) }}" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        @csrf
                        @method('PUT')
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label">@lang('auth.full_name')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ Auth::user()->name }}">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label">@lang('auth.email')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ Auth::user()->email }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selLocale" class="col-sm-4 col-form-label">prferred language</label>
                            <div class="col-sm-4">
                            <div class="input-group mb-3">
                              <select class='js-sel-locale js-states form-control select2' id='selLocale' name="locale">
                                 <option @if (Auth::user()->locale == 'en') selected @endif value="en">{{__('english')}}</option>
                                 <option @if (Auth::user()->locale == 'de') selected @endif value="de">{{__('deutsch')}}</option>
                              </select>
                              </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                      </form>
                  </div>
            </div>
          </div>
          @include('member.includes.member_edit')
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $("#updateMember").collapse("toggle");

    function formatLocale (locale) {

        var country = locale.id;
        if (country == "en"){
            country = 'gb';
        }
        var $locale = $(
            '<span class="flag-icon flag-icon-'+country+'"></span><span> '+locale.text+'</span></span>'
        );

        // Use .text() instead of HTML string concatenation to avoid script injection issues
        //$locale.find("span").text(locale.text);

        return $locale;
    };

    $("#selLocale").select2({
            theme: 'bootstrap4',
            multiple: false,
            allowClear: false,
            templateSelection: formatLocale
        });
</script>
@stop
