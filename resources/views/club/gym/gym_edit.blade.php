@extends('layouts.page')

@section('content')
<x-card-form cardTitle="{{ __('gym.title.edit', ['gymno' => $gym->gym_no, 'club'=> $gym->club['shortname']]) }}" formAction="{{ route('gym.update',['gym' => $gym]) }}" formMethod="PUT" >
    <div class="form-group row">
        <label class="col-sm-4 col-form-label" for='selGymno'>@lang('gym.no')</label>
        <div class="col-sm-6">
        <div class="input-group mb-3">
            <select class="js-sel-gymno js-states form-control select2  @error('gym_no') is-invalid @enderror" name="gym_no" id='gym_no'  @if ($gym->games()->exists()) disabled @endif >
            <option value="{{$gym->gym_no}}">{{$gym->gym_no}}</option>
            @foreach ( array_diff($allowed_gymno, $gym->club->gyms->pluck('gym_no')->toarray()) as $gymno )
            <option value="{{$gymno}}">{{$gymno}}</option>
            @endforeach
            </select>
            @error('gym_no')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <label for="name" class="col-sm-4 col-form-label">@lang('gym.name')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="@lang('gym.name')" value="{{ $gym->name }}">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row ">
        <label for="zip" class="col-sm-4 col-form-label">@lang('role.zipcode')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('zip') is-invalid @enderror" id="zip" name="zip" placeholder="@lang('role.zipcode')" value="{{ old('zip', $gym->zip) }}">
            @error('zip')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row ">
        <label for="city" class="col-sm-4 col-form-label">@lang('role.city')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="@lang('role.city')" value="{{ $gym->city }}">
            @error('city')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row ">
        <label for="street" class="col-sm-4 col-form-label">@lang('role.street')</label>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" placeholder="@lang('role.street')" value="{{ $gym->street }}">
            @error('street')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <x-slot name="addButtons">
        <button type="button" id="adrval" class="btn btn-secondary mr-2">{{ __('gym.action.validate_adr')}}</button>
    </x-slot>
</x-card-form>
@endsection

@section('js')
<script>
  $(function() {
        $('#frmClose').click(function(e){
            history.back();
        });

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
