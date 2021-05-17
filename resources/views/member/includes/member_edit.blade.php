<div class="col-md-4">
  <div class="card card-primary collapse" id="updateMember">
    <div class="card-header">
      <h3 class="card-title">@lang('role.title.edit', ['member'=> $member->name ])</h3>
    </div>
    <!-- /.card-header -->
      <div class="card-body">
          <form id="editMember" class="form-horizontal" action="{{ route('member.update',['member'=>$member]) }}" method="POST">
              @csrf
              @method('PUT')
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
                      <input type="text" class="form-control @error('phone1','err_member') is-invalid @enderror"
                        id="phone1" name="phone1" placeholder="@lang('role.phone1')" value="{{ old('phone1') ? old('phone1') : $member->phone1 }}"></input>
                      @error('phone1','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('phone2','err_member') is-invalid @enderror"
                        id="phone2" name="phone2" placeholder="@lang('role.phone2')" value="{{ old('phone2') ? old('phone2') : $member->phone2 }}"></input>
                      @error('phone2','err_member')
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
                      <input type="text" class="form-control @error('fax1','err_member') is-invalid @enderror"
                        id="fax1" name="fax1" placeholder="@lang('role.fax1')" value="{{ old('fax1') ? old('fax1') : $member->fax1 }}"></input>
                      @error('fax1','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('fax2','err_member') is-invalid @enderror"
                        id="fax2" name="fax2" placeholder="@lang('role.fax2')" value="{{ old('fax2') ? old('fax2') : $member->fax2 }}"></input>
                      @error('fax2','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <!--/.Content-->
              <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
          </form>
      </div>
  </div>
  <!--Modal: modalRelatedContent-->
  </div>
