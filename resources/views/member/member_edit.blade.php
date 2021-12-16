@extends('layouts.page')

@section('content')
<x-card-form cardTitle="{{ __('role.title.edit', ['member'=>$member->name]) }}" formAction="{{ route('member.update',['member' => $member]) }}" formMethod="PUT">
              <input type="hidden" id="backto" name="backto" value={{$backto}}>
              @if ($member->user()->exists())
              <div class="form-group row">
                <div class="col-sm-12">
                    <div class="alert alert-info" role="alert">{{__('role.hasuser')}}</div>
                </div>
              </div>
              @endif
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('firstname') is-invalid @enderror"
                        id="firstname" name="firstname" placeholder="@lang('role.firstname')" value="{{ old('firstname') ? old('firstname') : $member->firstname }}"></input>
                      @error('firstname')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('lastname') is-invalid @enderror"
                        id="lastname" name="lastname" placeholder="@lang('role.lastname')" value="{{ old('lastname') ? old('lastname') : $member->lastname }}"></input>
                      @error('lastname')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('street') is-invalid @enderror"
                        id="street" name="street" placeholder="@lang('role.street')" value="{{ old('street') ? old('street') : $member->street }}"></input>
                      @error('street')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('zipcode') is-invalid @enderror"
                        id="zipcode" name="zipcode" placeholder="@lang('role.zipcode')" value="{{ old('zipcode') ? old('zipcode') : $member->zipcode }}"></input>
                      @error('zipcode')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('city') is-invalid @enderror"
                        id="city" name="city" placeholder="@lang('role.city')" value="{{old('city') ? old('city') : $member->city }}"></input>
                      @error('city')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                        id="mobile" name="mobile" placeholder="@lang('role.mobile')" value="{{ old('mobile') ? old('mobile') : $member->mobile }}"></input>
                      @error('mobile')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('phone') is-invalid @enderror"
                        id="phone" name="phone" placeholder="@lang('role.phone')" value="{{ old('phone') ? old('phone') : $member->phone }}"></input>
                      @error('phone')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('email1') is-invalid @enderror"
                        id="email1" name="email1" placeholder="@lang('role.email1')" value="{{ old('email1') ? old('email1') : $member->email1 }}"></input>
                      @error('email1')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('email2') is-invalid @enderror"
                        id="email2" name="email2" placeholder="@lang('role.email2')" value="{{ old('email2') ? old('email2') : $member->email2 }}"></input>
                      @error('email2')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('fax') is-invalid @enderror"
                        id="fax" name="fax" placeholder="@lang('role.fax')" value="{{ old('fax') ? old('fax') : $member->fax }}"></input>
                      @error('fax')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
</x-card-form>
@endsection

@section('js')
<script>
        $(document).ready(function(){
            $('#frmClose').click(function(e){
                history.back();
            })
        });
</script>
@endsection
