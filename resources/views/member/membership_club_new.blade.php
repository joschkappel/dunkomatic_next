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
                    <h3 class="card-title">@lang('role.title.new', ['unittype'=> trans_choice('club.club',1), 'unitname' => $club->shortname ])</h3>
                </div>
                <!-- /.card-header -->
                    <div class="card-body">
                      <form id="newMembership" class="form-horizontal" action="{{ route('membership.club.store',['club' => $club ]) }}" method="POST">
                        @method('POST')
                        @csrf
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
                          <label class="col-sm-4 col-form-label" for='selRole'>{{trans_choice('role.role',1)}}</label>
                          <div class="col-sm-6">
                            <select class="js-sel-role js-states form-control select2  @error('selRole') is-invalid @enderror" multiple="multiple" name="selRole[]" id='selRole'></select>
                            @error('selRole')
                            <div class="invalid-feedback">PLs select at least one Role</div>
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
                          <label for="function" class="col-sm-4 col-form-label">@lang('role.member.action.create')</label>
                        </div>
                        <div class="form-group row">
                          <div class="col-sm-10">
                          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="button" id="btnCreateMember" class="btn btn-secondary" form="#" data-target="#createMember" data-toggle="collapse">Create New</button>
                            @if ($members->count() > 0)
                            <div class="btn-group" role="group">
                              <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Select from Club
                              </button>
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                @foreach ($members as $m)
                                  <a class="dropdown-item" href="#" onclick="show_member({{$m->id}},'{{$m->name}}','{{$m->street}}','{{$m->zipcode}}','{{$m->city}}','{{$m->email1}}','{{$m->phone1}}'); return false">{{ $m->name }}</a>
                                @endforeach
                              </div>
                            </div>
                            @endif
                            <button type="button" id="btnSelectMember" class="btn btn-secondary">Select from Region</button>
                            @if (!session('member'))
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
        @include('member.includes.member_new')
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
         show_member('','','','','','','','');
         $('#modalSelectMember').modal('show');
      });
      $("button#btnCreateMember").click( function(){
         show_member('','','','','','','','');
      });
      $("button#btnClear").click( function(){
         show_member('','','','','','','','');
         $("#createMember").collapse("hide");
      });
      $(".js-sel-role").select2({
          placeholder: "@lang('role.action.select')...",
          theme: 'bootstrap4',
          multiple: true,
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('role.index')}}",
                  type: "POST",
                  delay: 250,
                  dataType: "json",
                  data: {
                       "_token": "{{ csrf_token() }}",
                       "scope": 'ALL'
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
                  url: "{{ route('member.region.sb', ['region' => $club->region()->first()->id]) }}",
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
