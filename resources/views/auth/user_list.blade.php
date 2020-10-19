@extends('layouts.page')

@section('plugins.Datatables', true)

@section('content')

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">@lang('user.title.list', ['region' => Auth::user()->region ])</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
                  </div>
                  <div class="card-body">

         <table class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>@lang('auth.full_name')</th>
                  <th>@lang('auth.email')</th>
                  <th>@lang('auth.user.clubs')</th>
                  <th>@lang('auth.user.leagues')</th>
                  <th>{{__('Created at')}}</th>
                  <th>{{__('Email verfified at')}}</th>
                  <th>{{__('Approved at')}}</th>
                  <th>{{__('Rejected at')}}</th>
               </tr>
            </thead>
         </table>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
@stop

@section('js')
<script>
         $(function() {
               $('#table').DataTable({
               processing: true,
               serverSide: true,
               order: [[1,'asc']],
               ajax: '{{ route('admin.user.dt', app()->getLocale()) }}',
               columns: [
                        { data: 'id', name: 'id', visible: false },
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'clubs', name: 'clubs' },
                        { data: 'leagues', name: 'leagues' },
                        { data: {
                           _: 'created_at.filter',
                           display: 'created_at.display',
                           sort: 'created_at.ts'
                         }, name: 'created_at.ts' },
                       { data: {
                          _: 'email_verified_at.filter',
                          display: 'email_verified_at.display',
                          sort: 'email_verified_at.ts'
                        }, name: 'email_verified_at.ts' },
                        { data: {
                           _: 'approved_at.filter',
                           display: 'approved_at.display',
                           sort: 'approved_at.ts'
                         }, name: 'approved_at.ts' },
                         { data: {
                            _: 'rejected_at.filter',
                            display: 'rejected_at.display',
                            sort: 'rejected_at.ts'
                          }, name: 'rejected_at.ts' },
                     ]
            });
         });


</script>
@stop
