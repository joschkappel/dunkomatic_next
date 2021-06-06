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
                    <h3 class="card-title">@lang('gym.title.new', ['club' =>  $club->shortname])</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('club.gym.store', ['club' => $club ]) }}" method="POST">
                    <div class="card-body">
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif
                        <input type="hidden" name="club_id" value="{{ $club->id}}">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for='selGymno'>@lang('gym.no')</label>
                            <div class="col-sm-6">
                              <select class="js-sel-gymno js-states form-control select2  @error('gym_no') is-invalid @enderror" name="gym_no" id='gym_no'>
                              @foreach ( array_diff($allowed_gymno, $club->gyms->pluck('gym_no')->toarray()) as $gymno )
                                <option value="{{$gymno}}">{{$gymno}}</option>
                              @endforeach
                              </select>
                              @error('gym_no')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>
                        </div>

                        <div class="form-group row ">
                            <label for="title" class="col-sm-4 col-form-label">@lang('gym.name')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="@lang('gym.name')" value="{{ old('name')}}">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="title" class="col-sm-4 col-form-label">@lang('role.zipcode')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('zip') is-invalid @enderror" id="zip" name="zip" placeholder="@lang('role.zipcode')" value="{{ old('zip')}}">
                                @error('zip')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="title" class="col-sm-4 col-form-label">@lang('role.city')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="@lang('role.city')" value="{{ old('city')}}">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="title" class="col-sm-4 col-form-label">@lang('role.street')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" placeholder="@lang('role.street')" value="{{ old('street')}}">
                                @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">

                            <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                            <button type="button" id="adrval" class="btn btn-info">{{ __('gym.action.validate_adr')}}</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
  $(function() {
    $("button#adrval").click( function(){
       var street = $('#street').val();
       var city = $('#city').val();
       var zip = $('#zip').val();

       var uri= "{{ config('dunkomatic.maps_uri') }}"+street+', '+zip+' '+city;
       var res = encodeURI(uri);
       window.open(res, "_blank");
    });

    $(".js-sel-gymno").select2({
          placeholder: "@lang('gym.no')...",
          theme: 'bootstrap4',
          multiple: false,
          allowClear: false,
      });
  });
</script>
@endsection
