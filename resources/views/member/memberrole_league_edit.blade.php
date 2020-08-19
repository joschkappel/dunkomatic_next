@extends('adminlte::page')

@section('plugins.Select2', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-10">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('role.title.edit', ['member'=> $member->firstname.' '.$member->lastname] )</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('league.memberrole.update',['memberrole' => $member, 'league' => $league]) }}" method="POST">
                    <div class="card-body">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="old_role_id" value="{{ $member_roles->role['id'] }}">
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                           @lang('Please fix the following errors')
                        </div>
                        @endif
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label" for='selRole'>{{trans_choice('role.role',1)}}</label>
                          <div class="col-sm-10">
                            <select class='js-placeholder-single js-states form-control select2 ' disabled  name="selRole" id='selRole'>
                               <option value="{{ $member_roles->role['id'] }}" selected>{{ $member_roles->role['name'] }}</option>
                            </select>
                          </div>
                            </div>
                        <div class="form-group row">
                            <label for="firstname" class="col-sm-2 col-form-label">@lang('role.firstname')</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" placeholder="@lang('role.firstname')" value="{{ $member->firstname }}">
                                @error('firstname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="lastname" class="col-sm-2 col-form-label">@lang('role.lastname')</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" placeholder="@lang('role.lastname')" value="{{ $member->lastname}}">
                                @error('lastname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="street" class="col-sm-2 col-form-label">@lang('role.street')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" placeholder="@lang('role.street')" value="{{ $member->street}}">
                                @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                          </div>
                        <div class="form-group row">
                            <label for="zipcode" class="col-sm-2 col-form-label">@lang('role.zipcode')</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control @error('zipcode') is-invalid @enderror" id="zipcode" name="zipcode" placeholder="@lang('role.zipcode')" value="{{ $member->zipcode }}">
                                @error('zipcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="city" class="col-sm-2 col-form-label">@lang('role.city')</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="@lang('role.city')" value="{{ $member->city }}">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mobile" class="col-sm-2 col-form-label">@lang('role.mobile')</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" placeholder="@lang('role.mobile')" value="{{ $member->mobile }}">
                                @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone1" class="col-sm-2 col-form-label">@lang('role.phone1')</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('phone1') is-invalid @enderror" id="phone1" name="phone1" placeholder="@lang('role.phone1')" value="{{ $member->phone1 }}">
                                @error('phone1')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="phone2" class="col-sm-2 col-form-label">@lang('role.phone2')</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('phone2') is-invalid @enderror" id="phone2" name="phone2" placeholder="@lang('role.phone2')" value="{{ $member->phone2 }}">
                                @error('phone2')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email1" class="col-sm-2 col-form-label">@lang('role.email1')</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('email1') is-invalid @enderror" id="email1" name="email1" placeholder="@lang('role.email1')" value="{{ $member->email1 }}">
                                @error('email1')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="email2" class="col-sm-2 col-form-label">@lang('role.email2')</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('email2') is-invalid @enderror" id="email2" name="email2" placeholder="@lang('role.email2')" value="{{ $member->email2 }}">
                                @error('email2')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fax1" class="col-sm-2 col-form-label">@lang('role.fax1')</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('fax1') is-invalid @enderror" id="fax1" name="fax1" placeholder="@lang('role.fax1')" value="{{ $member->fax1 }}">
                                @error('fax1')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="fax2" class="col-sm-2 col-form-label">@lang('role.fax2')</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('fax2') is-invalid @enderror" id="fax2" name="fax2" placeholder="@lang('role.fax2')" value="{{ $member->fax2 }}">
                                @error('fax2')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
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

      $(".js-placeholder-single").select2({
          placeholder: "@lang('role.action.select')...",
          multiple: false,
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('role.index')}}",
                  type: "POST",
                  dataType: "json",
                  data: {
                       "_token": "{{ csrf_token() }}",
                       "scope": 'LEAGUE'
                   },
                  delay: 250,
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
      });


    });

</script>


@stop
