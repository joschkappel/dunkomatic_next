<div class="col-md-4">
  <div class="card card-primary collapse" id="createMember">
    <div class="card-header">
      @if (isset($club))
        <h3 class="card-title">@lang('role.title.new', ['unittype'=> trans_choice('club.club',1), 'unitname' => $club->shortname ])</h3>
      @elseif (isset($league))
        <h3 class="card-title">@lang('role.title.new', ['unittype'=> trans_choice('league.league',1), 'unitname' => $league->shortname ])</h3>
      @else
        <h3 class="card-title">@lang('role.title.new', ['unittype'=> @lang('auth.user'), 'unitname' => '' ])</h3>        
      @endif
    </div>
    <!-- /.card-header -->
      <div class="card-body">
          <form id="newMember" class="form-horizontal" action="{{ route('member.store') }}" method="POST">
              @csrf
              @method('POST')
              @if ($errors->err_member->any())
              <div class="alert alert-danger" role="alert">
                 @lang('Please fix the following errors')
              </div>
              @endif
              @if (isset($club))
                <input type="hidden" id="club_id" name="club_id" value="{{ $club->id}}"></input>
              @else
                <input type="hidden" id="league_id" name="leauge_id" value="{{ $league->id}}"></input>
              @endif
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('firstname','err_member') is-invalid @enderror"
                        id="firstname" name="firstname" placeholder="@lang('role.firstname')" value="{{ old('firstname') }}"></input>
                      @error('firstname','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('lastname','err_member') is-invalid @enderror"
                        id="lastname" name="lastname" placeholder="@lang('role.lastname')" value="{{ old('lastname') }}"></input>
                      @error('lastname','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('street','err_member') is-invalid @enderror"
                        id="street" name="street" placeholder="@lang('role.street')" value="{{ old('street')}}"></input>
                      @error('street','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('zipcode','err_member') is-invalid @enderror"
                        id="zipcode" name="zipcode" placeholder="@lang('role.zipcode')" value="{{ old('zipcode') }}"></input>
                      @error('zipcode','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('city','err_member') is-invalid @enderror"
                        id="city" name="city" placeholder="@lang('role.city')" value="{{old('city') }}"></input>
                      @error('city','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('mobile','err_member') is-invalid @enderror"
                        id="mobile" name="mobile" placeholder="@lang('role.mobile')" value="{{ old('mobile') }}"></input>
                      @error('mobile','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('phone1','err_member') is-invalid @enderror"
                        id="phone1" name="phone1" placeholder="@lang('role.phone1')" value="{{ old('phone1') }}"></input>
                      @error('phone1','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('phone2','err_member') is-invalid @enderror"
                        id="phone2" name="phone2" placeholder="@lang('role.phone2')" value="{{ old('phone2') }}"></input>
                      @error('phone2','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('email1','err_member') is-invalid @enderror"
                        id="email1" name="email1" placeholder="@lang('role.email1')" value="{{ old('email1') }}"></input>
                      @error('email1','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('email2','err_member') is-invalid @enderror"
                        id="email2" name="email2" placeholder="@lang('role.email2')" value="{{ old('email2') }}"></input>
                      @error('email2','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('fax1','err_member') is-invalid @enderror"
                        id="fax1" name="fax1" placeholder="@lang('role.fax1')" value="{{ old('fax1') }}"></input>
                      @error('fax1','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-6">
                      <input type="text" class="form-control @error('fax2','err_member') is-invalid @enderror"
                        id="fax2" name="fax2" placeholder="@lang('role.fax2')" value="{{ old('fax2') }}"></input>
                      @error('fax2','err_member')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
              <div class="form-group  clearfix">
                <div class="icheck-info d-inline">
                  <input type="checkbox" id="user_account" name="user_account" >
                  <label for="user_account" >@lang('role.user.account')</label>
                </div>
              </div>
              <!--/.Content-->
              <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
          </form>
      </div>
  </div>
  <!--Modal: modalRelatedContent-->
  </div>
