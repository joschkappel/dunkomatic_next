<div class="form-group row">
  <label for="function" class="col-sm-2 col-form-label">@lang('role.function')</label>
  <div class="col-sm-8">
      <input type="text" class="form-control @error('function') is-invalid @enderror" id="function" name="function" placeholder="@lang('role.function')" value="{{ old('function') }}">
      @error('function')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
  </div>
</div>
<div class="form-group row">
  <label class="col-sm-2 col-form-label" for='selMember'>{{trans_choice('role.member',1)}}</label>
  <div class="col-sm-8">
    <select class='js-placeholder-single js-states form-control select2' name="selMember" id='selMember'>
    </select>
  </div>
</div>
<div class="form-group row">
    <label for="firstname" class="col-sm-2 col-form-label">@lang('role.firstname')</label>
    <div class="col-sm-2">
        <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" placeholder="@lang('role.firstname')" value="{{ old('firstname') }}">
        @error('firstname')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <label for="lastname" class="col-sm-2 col-form-label">@lang('role.lastname')</label>
    <div class="col-sm-4">
        <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" placeholder="@lang('role.lastname')" value="{{ old('lastname') }}">
        @error('lastname')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="street" class="col-sm-2 col-form-label">@lang('role.street')</label>
    <div class="col-sm-8">
        <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" placeholder="@lang('role.street')" value="{{ old('street')}}">
        @error('street')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
  </div>
<div class="form-group row">
    <label for="zipcode" class="col-sm-2 col-form-label">@lang('role.zipcode')</label>
    <div class="col-sm-2">
        <input type="text" class="form-control @error('zipcode') is-invalid @enderror" id="zipcode" name="zipcode" placeholder="@lang('role.zipcode')" value="{{ old('zipcode') }}">
        @error('zipcode')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <label for="city" class="col-sm-2 col-form-label">@lang('role.city')</label>
    <div class="col-sm-4">
        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="@lang('role.city')" value="{{old('city') }}">
        @error('city')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="mobile" class="col-sm-2 col-form-label">@lang('role.mobile')</label>
    <div class="col-sm-4">
        <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" placeholder="@lang('role.mobile')" value="{{ old('mobile') }}">
        @error('mobile')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="phone1" class="col-sm-2 col-form-label">@lang('role.phone1')</label>
    <div class="col-sm-4">
        <input type="text" class="form-control @error('phone1') is-invalid @enderror" id="phone1" name="phone1" placeholder="@lang('role.phone1')" value="{{ old('phone1') }}">
        @error('phone1')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <label for="phone2" class="col-sm-2 col-form-label">@lang('role.phone2')</label>
    <div class="col-sm-4">
        <input type="text" class="form-control @error('phone2') is-invalid @enderror" id="phone2" name="phone2" placeholder="@lang('role.phone2')" value="{{ old('phone2') }}">
        @error('phone2')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="email1" class="col-sm-2 col-form-label">@lang('role.email1')</label>
    <div class="col-sm-4">
        <input type="text" class="form-control @error('email1') is-invalid @enderror" id="email1" name="email1" placeholder="@lang('role.email1')" value="{{ old('email1') }}">
        @error('email1')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <label for="email2" class="col-sm-2 col-form-label">@lang('role.email2')</label>
    <div class="col-sm-4">
        <input type="text" class="form-control @error('email2') is-invalid @enderror" id="email2" name="email2" placeholder="@lang('role.email2')" value="{{ old('email2') }}">
        @error('email2')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="fax1" class="col-sm-2 col-form-label">@lang('role.fax1')</label>
    <div class="col-sm-4">
        <input type="text" class="form-control @error('fax1') is-invalid @enderror" id="fax1" name="fax1" placeholder="@lang('role.fax1')" value="{{ old('fax1') }}">
        @error('fax1')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <label for="fax2" class="col-sm-2 col-form-label">@lang('role.fax2')</label>
    <div class="col-sm-4">
        <input type="text" class="form-control @error('fax2') is-invalid @enderror" id="fax2" name="fax2" placeholder="@lang('role.fax2')" value="{{ old('fax2') }}">
        @error('fax2')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
