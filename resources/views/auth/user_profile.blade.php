@extends('layouts.page')

@section('content')
<x-card-form cardTitle="{{ __('auth.title.edit') }}" formAction="{{ route('admin.user.update', ['user' => Auth::user()]) }}" formMethod="PUT" >
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
</x-card-form>
@include('member.includes.member_edit')
@endsection

@section('js')
<script>
    $('#frmClose').click(function(e){
        history.back();
    });
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
