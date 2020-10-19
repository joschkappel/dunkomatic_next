@extends('layouts.page')

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
                <form class="form-horizontal" action="{{ route('league.membership.update',['membership' => $member, 'league' => $league]) }}" method="POST">
                    <div class="card-body">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="old_role_id" value="{{ $member_roles->role_id }}">
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                           @lang('Please fix the following errors')
                        </div>
                        @endif
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label" for='selRole'>{{trans_choice('role.role',1)}}</label>
                          <div class="col-sm-10">
                            <select class='js-placeholder-single js-states form-control select2 ' disabled  name="selRole" id='selRole'>
                               <option value="{{ $member_roles->role_id }}" selected>{{ App\Enums\Role::getDescription($member_roles->role_id) }}</option>
                            </select>
                          </div>
                            </div>
                            @include('member.includes.member_edit')
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
