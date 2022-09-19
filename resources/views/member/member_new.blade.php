@extends('layouts.page')

@section('css')
<style>

</style>
@endsection

@section('content')
@php
  if ($entity_type == 'App\Models\Club'){
    $title = __('role.title.new', ['unittype'=> trans_choice('club.club',1), 'unitname' => $entity->shortname ]);
  } elseif ($entity_type == 'App\Models\League'){
    $title = __('role.title.new', ['unittype'=> trans_choice('league.league',1), 'unitname' => $entity->shortname ]);
  } elseif ($entity_type == 'App\Models\Region'){
    $title = __('role.title.new', ['unittype'=> trans_choice('region.region',1), 'unitname' => $entity->code ]);
  } elseif ($entity_type == 'App\Models\Team'){
    $title = __('role.title.new', ['unittype'=> trans_choice('team.team',1), 'unitname' => $entity->name ]);
  }
@endphp

<x-card-form :cardTitle="$title" formAction="{{ route('member.store') }}">
    <input type="hidden" id="entity_type" name="entity_type" value="{{ $entity_type }}">
    <input type="hidden" id="entity_id" name="entity_id" value="{{ $entity->id }}">
    <input type="hidden" id="member_id" name="member_id" value="">
    @if (session('member'))
        <div class="alert alert-success">
          {{ __('member.confirm.created') }}: {{ session('member')->name }}
        </div>
    @endif
        <div class="form-group row">
            <div class="col-sm-12">
            <div class="input-group mb-3">
              <select class="js-sel-role js-states form-control select2  @error('role_id') is-invalid @enderror" name="role_id" id='role_id'></select>
              @error('role_id')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              </div>
            </div>
          </div>
          <div class="form-group row">
              <div class="col-sm-6">
                  <input type="text" class="form-control @error('email') is-invalid @enderror"
                    id="email" name="email" placeholder="@lang('role.role.email')" value="{{ old('email') }}"></input>
                  @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="col-sm-6">
                <input type="text" class="form-control @error('function') is-invalid @enderror" id="function" name="function" placeholder="@lang('role.function')" value="{{ old('function') }}">
                @error('function')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
          </div>
          <div class="d-flex py-4">
            <hr class="my-auto flex-grow-1 border-dark">
            <div class="px-4 text-info text-strong">Diese Funktion wird folgendem Mitarbeitenden zugeordnet:</div>
            <hr class="my-auto flex-grow-1 border-dark">
          </div>

        <div class="form-group row">
            <div class="col-sm-10">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <button type="button" id="btnSelectMember" class="btn btn-secondary">@lang('role.member.action.select')</button>
                <button type="button" id="btnClear" class="btn btn-secondary">@lang('Clear')</button>
                </div>
            </div>
        </div>
        <div class="form-group row">

        <div class="col-sm-6">
            <input type="text" class="form-control @error('firstname') is-invalid @enderror"
              id="firstname" name="firstname" placeholder="@lang('role.firstname')" value="{{ old('firstname') }}"></input>
            @error('firstname')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('lastname') is-invalid @enderror"
              id="lastname" name="lastname" placeholder="@lang('role.lastname')" value="{{ old('lastname') }}"></input>
            @error('lastname')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-6">
            <input type="text" class="form-control @error('street') is-invalid @enderror"
              id="street" name="street" placeholder="@lang('role.street')" value="{{ old('street')}}"></input>
            @error('street')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-6">
            <input type="text" class="form-control @error('zipcode') is-invalid @enderror"
              id="zipcode" name="zipcode" placeholder="@lang('role.zipcode')" value="{{ old('zipcode') }}"></input>
            @error('zipcode')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('city') is-invalid @enderror"
              id="city" name="city" placeholder="@lang('role.city')" value="{{old('city') }}"></input>
            @error('city')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-6">
            <input type="text" class="form-control @error('mobile') is-invalid @enderror"
              id="mobile" name="mobile" placeholder="@lang('role.mobile')" value="{{ old('mobile') }}"></input>
            @error('mobile')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('phone') is-invalid @enderror"
              id="phone" name="phone" placeholder="@lang('role.phone')" value="{{ old('phone') }}"></input>
            @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    </div>
    <div class="form-group row">
        <div class="col-sm-6">
            <input type="text" class="form-control @error('email1') is-invalid @enderror"
              id="email1" name="email1" placeholder="@lang('role.email1')" value="{{ old('email1') }}"></input>
            @error('email1')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-6">
            <input type="text" class="form-control @error('email2') is-invalid @enderror"
              id="email2" name="email2" placeholder="@lang('role.email2')" value="{{ old('email2') }}"></input>
            @error('email2')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-6">
            <input type="text" class="form-control @error('fax') is-invalid @enderror"
              id="fax" name="fax" placeholder="@lang('role.fax')" value="{{ old('fax') }}"></input>
            @error('fax')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</x-card-form>
@include('member.includes.member_select')
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
      $('#frmClose').click(function(e){
            history.back();
      });


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
          width: '100%',
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
          width: '100%',
          multiple: false,
          allowClear: true,
          minimumResultsForSearch: -1,
          ajax: {
                  @if ($entity_type == 'App\Models\Region')
                    url: "{{ route('member.sb.region', ['region' => $entity->id]) }}",
                  @elseif ($entity_type == 'App\Models\Team')
                    url: "{{ route('member.sb.club', ['club' => $entity->club->id]) }}",
                  @else
                    url: "{{ route('member.sb.region', ['region' => $entity->region->id]) }}",
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
