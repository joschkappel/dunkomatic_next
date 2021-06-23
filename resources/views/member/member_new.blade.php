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
                  @if ( $entity_type == 'App\Models\Club' )
                    <h3 class="card-title">@lang('role.title.new', ['unittype'=> trans_choice('club.club',1), 'unitname' => $entity->shortname ])</h3>
                  @elseif ( $entity_type == 'App\Models\League' )
                    <h3 class="card-title">@lang('role.title.new', ['unittype'=> trans_choice('league.league',1), 'unitname' => $entity->shortname ])</h3>
                  @elseif ( $entity_type == 'App\Models\Region' )
                    <h3 class="card-title">@lang('role.title.new', ['unittype'=> trans_choice('region.region',1), 'unitname' => $entity->code ])</h3>
                  @endif
                </div>
                <!-- /.card-header -->
                    <div class="card-body">
                      <form id="newMembership" class="form-horizontal" action="{{ route('member.store') }}" method="POST">
                        @method('POST')
                        @csrf
                        <input type="hidden" id="entity_type" name="entity_type" value="{{ $entity_type }}">
                        <input type="hidden" id="entity_id" name="entity_id" value="{{ $entity->id }}">
                        <input type="hidden" id="member_id" name="member_id" value="">
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                           @lang('Please fix the following errors')
                        </div>
                        @endif
                        @if (session('member'))
                            <div class="alert alert-success">
                              New Member created: {{ session('member')->name }}
                            </div>
                        @endif
                        <div class="form-group row">
                          <div class="col-sm-10">
                          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="button" id="btnSelectMember" class="btn btn-secondary">Select from Region</button>
                            <button type="button" id="btnClear" class="btn btn-secondary">Clear</button>
                          </div>
                          </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('firstname','err_member') is-invalid @enderror"
                                  id="firstname" name="firstname" placeholder="@lang('role.firstname')" value="{{ old('firstname') }}"></input>
                                @error('firstname','err_member')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                                <input type="text" class="form-control @error('phone','err_member') is-invalid @enderror"
                                  id="phone" name="phone" placeholder="@lang('role.phone')" value="{{ old('phone') }}"></input>
                                @error('phone','err_member')
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
                                <input type="text" class="form-control @error('fax','err_member') is-invalid @enderror"
                                  id="fax" name="fax" placeholder="@lang('role.fax')" value="{{ old('fax') }}"></input>
                                @error('fax','err_member')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label" for='role_id'>{{trans_choice('role.role',1)}}</label>
                          <div class="col-sm-6">
                            <select class="js-sel-role js-states form-control select2  @error('role_id') is-invalid @enderror" name="role_id" id='role_id'></select>
                            @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="function" class="col-sm-4 col-form-label">@lang('role.function')</label>
                          <div class="col-sm-6">
                              <input type="text" class="form-control @error('function') is-invalid @enderror" id="function" name="function" placeholder="@lang('role.function')" value="{{ old('function') }}">
                              @error('function')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label">@lang('role.email1')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('email','err_member') is-invalid @enderror"
                                  id="email" name="email" placeholder="@lang('role.email1')" value="{{ old('email') }}"></input>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>                        

                        <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                      </form>
                    </div>
            </div>
              </div>
        @include('member.includes.member_select')
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function show_member(id, firstname, lastname,  street, zipcode, city, email1, email2, mobile, phone, fax) {
      $('#firstname').val(firstname);
      $('#lastname').val(lastname);
      $('#street').val(street);
      $('#zipcode').val(zipcode);
      $('#city').val(city);
      $('#email1').val(email1);
      $('#email2').val(email2);
      $('#mobile').val(mobile);
      $('#phone').val(phone);
      $('#fax').val(fax);
      $('#member_id').val(id);
    }

    $(function() {
      @if ($errors->err_member->any())
      $("#createMember").collapse("toggle");
      @endif

      @if (session('member'))
       show_member( {{ session('member')->id }},
                    '{{ session('member')->name }}',
                    '{{ session('member')->street }}',
                    '{{ session('member')->zipcode }}',
                    '{{ session('member')->city }}',
                    '{{ session('member')->email1 }}',
                    '{{ session('member')->mobile }}');
      @endif

      $("button#btnSelectMember").click( function(){
         show_member('','','','','','','','','','','');
         $('#modalSelectMember').modal('show');
      });
      $("button#btnClear").click( function(){
         show_member('','','','','','','','','','','');
      });
      $(".js-sel-role").select2({
          placeholder: "@lang('role.action.select')...",
          theme: 'bootstrap4',
          multiple: false,
          allowClear: false,
          ajax: {
                  url: "{{ route('role.index')}}",
                  type: "POST",
                  delay: 250,
                  dataType: "json",
                  data: {
                       _token: "{{ csrf_token() }}",
                       scope: $('#entity_type').val()
                   },
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
      });

      $(".js-sel-member").select2({
          placeholder: "@lang('role.member.action.select')...",
          theme: 'bootstrap4',
          multiple: false,
          allowClear: true,
          minimumResultsForSearch: -1,
          ajax: {
                  @if ($entity_type == 'App\Models\Region')
                    url: "{{ route('member.sb.region', ['region' => $entity->id]) }}",
                  @else
                    url: "{{ route('member.sb.region', ['region' => $entity->region()->first()->id]) }}",
                  @endif
                  type: "get",
                  delay: 250,
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
      });


      $('#selMember').on('select2:select', function(e) {
                var values = $('#selMember').select2('data');
                var selVals = values.map(function(elem) {
                  return {
                    id: elem.id,
                    text: elem.text
                  };
                });

                console.log(selVals);
                var url = "{{ route('member.show', ['language'=>app()->getLocale(), 'member'=>':member:']) }}";
                url = url.replace(':member:', selVals[0].id);
                $.ajax({
                  type: 'GET',
                  url: url,
                  success: function (data) {
                    show_member(data.id,
                                data.firstname, 
                                data.lastname,
                                data.street,
                                data.zipcode,
                                data.city,
                                data.email1,
                                data.email2,
                                data.mobile,
                                data.phone,
                                data.fax,
                                );
                  },
                });
            });

      $('#selMember').on('select2:unselect select2:clear', function(e) {
          show_member('','','','','','','','','','','');
      });

    });

</script>


@stop
