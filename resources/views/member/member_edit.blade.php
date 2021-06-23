@extends('layouts.page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title">@lang('role.title.edit', ['member'=> $member->name ])</h3>
    </div>
    <!-- /.card-header -->
      <div class="card-body">
          <form id="editMember" class="form-horizontal" action="{{ route('member.update',['member'=>$member]) }}" method="POST">
              @csrf
              @method('PUT')
              <input type="hidden" id="backto" name="backto" value={{$backto}}>
              @if ($errors->err_member->any())
              <div class="alert alert-danger" role="alert">
                 @lang('Please fix the following errors')
              </div>
              @endif
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('firstname','err_member') is-invalid @enderror"
                        id="firstname" name="firstname" placeholder="@lang('role.firstname')" value="{{ old('firstname') ? old('firstname') : $member->firstname }}"></input>
                      @error('firstname','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('lastname','err_member') is-invalid @enderror"
                        id="lastname" name="lastname" placeholder="@lang('role.lastname')" value="{{ old('lastname') ? old('lastname') : $member->lastname }}"></input>
                      @error('lastname','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('street','err_member') is-invalid @enderror"
                        id="street" name="street" placeholder="@lang('role.street')" value="{{ old('street') ? old('street') : $member->street }}"></input>
                      @error('street','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('zipcode','err_member') is-invalid @enderror"
                        id="zipcode" name="zipcode" placeholder="@lang('role.zipcode')" value="{{ old('zipcode') ? old('zipcode') : $member->zipcode }}"></input>
                      @error('zipcode','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('city','err_member') is-invalid @enderror"
                        id="city" name="city" placeholder="@lang('role.city')" value="{{old('city') ? old('city') : $member->city }}"></input>
                      @error('city','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('mobile','err_member') is-invalid @enderror"
                        id="mobile" name="mobile" placeholder="@lang('role.mobile')" value="{{ old('mobile') ? old('mobile') : $member->mobile }}"></input>
                      @error('mobile','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('phone','err_member') is-invalid @enderror"
                        id="phone" name="phone" placeholder="@lang('role.phone')" value="{{ old('phone') ? old('phone') : $member->phone }}"></input>
                      @error('phone','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('email1','err_member') is-invalid @enderror"
                        id="email1" name="email1" placeholder="@lang('role.email1')" value="{{ old('email1') ? old('email1') : $member->email1 }}"></input>
                      @error('email1','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('email2','err_member') is-invalid @enderror"
                        id="email2" name="email2" placeholder="@lang('role.email2')" value="{{ old('email2') ? old('email2') : $member->email2 }}"></input>
                      @error('email2','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('fax','err_member') is-invalid @enderror"
                        id="fax" name="fax" placeholder="@lang('role.fax')" value="{{ old('fax') ? old('fax') : $member->fax }}"></input>
                      @error('fax','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <!--/.Content-->
              <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
          </form>
      </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')

@endsection

@section('js')
<script>
</script>


@stop
