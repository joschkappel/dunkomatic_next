@extends('layouts.page')

@section('plugins.ICheck',true)
@section('plugins.Select2', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('role.title.edit', ['member'=> $member->firstname.' '.$member->lastname] )</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form id="editMembership" class="form-horizontal" action="{{ route('membership.region.update',['region' => $region, 'member'=>$member]) }}" method="POST">
                        @method('PUT')
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                           @lang('Please fix the following errors')
                        </div>
                        @endif
                        @if (session('member_mod'))
                            <div class="alert alert-success">
                              Member updated: {{ session('member_mod')->name }}
                            </div>
                        @endif
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label" for='selRole'>{{trans_choice('role.role',1)}}</label>
                          <div class="col-sm-6">
                            <select class='js-sel-role js-states form-control select2 '  name="selRole" id='selRole'>
                               @foreach ($membership as $mship)
                               <option value="{{ $mship->role_id }}" selected>{{ App\Enums\Role::getDescription($mship->role_id) }}</option>
                              @endforeach
                            </select>
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
                        <div class="form-group row">
                          <label for="function" class="col-sm-4 col-form-label">@lang('role.member.action.create')</label>
                        </div>
                        <div class="form-group row">
                          <div class="col-sm-10">
                          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="button" id="btnUpdateMember" class="btn btn-secondary" form="#" data-target="#updateMember" data-toggle="collapse">Modify Member</button>
                            @if ($members->count() > 0)
                            <div class="btn-group" role="group">
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                @foreach ($members as $m)
                                  <a class="dropdown-item" href="#" onclick="show_member({{$m->id}},'{{$m->name}}','{{$m->street}}','{{$m->zipcode}}','{{$m->city}}','{{$m->email1}}','{{$m->phone1}}'); return false">{{ $m->name }}</a>
                                @endforeach
                              </div>
                            </div>
                            @endif
                            <button type="button" id="btnSelectMember" class="btn btn-secondary">Select from Region</button>
                            @if (!session('member_mod'))
                            <button type="button" id="btnClear" class="btn btn-secondary">Clear</button>
                          @endif
                          </div>
                          </div>
                        </div>
                        @include('member.includes.member_show')

                        <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                      </form>
                    </div>
                </div>
                  </div>
            @include('member.includes.member_edit')
            @include('member.includes.member_select')
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    function show_member(id, name, street, zipcode, city, email1, mobile) {
      $('#mname').val(name);
      $('#mstreet').val(street);
      $('#mzipcode').val(zipcode);
      $('#mcity').val(city);
      $('#memail1').val(email1);
      $('#mmobile').val(mobile);
      $('#member_id').val(id);
    }

    $(function() {
      @if ($errors->err_member->any())
        $("#updateMember").collapse("toggle");
      @endif

      @if (session('member_mod'))
       show_member( {{ session('member_mod')->id }},
                    '{{ session('member_mod')->name }}',
                    '{{ session('member_mod')->street }}',
                    '{{ session('member_mod')->zipcode }}',
                    '{{ session('member_mod')->city }}',
                    '{{ session('member_mod')->email1 }}',
                    '{{ session('member_mod')->mobile }}');
      @else
        show_member( {{ $member->id }},
                     '{{ $member->name }}',
                     '{{ $member->street }}',
                     '{{ $member->zipcode }}',
                     '{{ $member->city }}',
                     '{{ $member->email1 }}',
                     '{{ $member->mobile }}');
      @endif

      $("button#btnSelectMember").click( function(){
         $('#modalSelectMember').modal('show');
      });
      $("button#btnClear").click( function(){
        show_member( {{ $member->id }},
                     '{{ $member->name }}',
                     '{{ $member->street }}',
                     '{{ $member->zipcode }}',
                     '{{ $member->city }}',
                     '{{ $member->email1 }}',
                     '{{ $member->mobile }}');
         $("#updateMember").collapse("hide");
      });

      $(".js-sel-role").select2({
          placeholder: "@lang('role.action.select')...",
          theme: 'bootstrap4',
          multiple: false,
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('role.index')}}",
                  type: "POST",
                  dataType: "json",
                  data: {
                       "_token": "{{ csrf_token() }}",
                       "scope": 'REGION'
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
      $(".js-sel-member").select2({
          placeholder: "@lang('role.member.action.select')...",
          theme: 'bootstrap4',
          multiple: false,
          allowClear: true,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('member.sb.region', ['region' => $region->id]) }}",
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
                                data.firstname+' '+data.lastname,
                                data.street,
                                data.zipcode,
                                data.city,
                                data.email1,
                                data.mobile);
                  },
                });
            });

      $('#selMember').on('select2:unselect select2:clear', function(e) {
          show_member('','','','','','','','');
      });


    });

</script>


@stop
