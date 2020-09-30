@extends('layouts.page')

@section('plugins.Datatables', true)

@section('content_header')
    @if(Session::has('success'))
    <div class="alert alert-success">
        {{Session::get('success')}}
    </div>
@endif
@stop

@section('content')

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">@lang('club.title.list', ['region' => Auth::user()->region ])</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
                    <a href="{{ route('club.create', app()->getLocale()) }}" class="text-center btn btn-success btn-sm mb-3">@lang('club.action.create')</a>
          </div>
          <div class="card-body">

         <table class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>@lang('club.shortname')</th>
                  <th>@lang('club.name')</th>
                  <th>@lang('club.region')</th>
                  <th>{{__('Created at')}}</th>
               </tr>
            </thead>
         </table>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
@stop

@section('footer')
jochenk
@stop


@section('js')
<script>
         $(function() {
               $('#table').DataTable({
               processing: true,
               serverSide: true,
               order: [[1,'asc']],
               ajax: '{{ route('club.list', app()->getLocale()) }}',
               columns: [
                        { data: 'id', name: 'id', visible: false },
                        { data: 'shortname', name: 'shortname' },
                        { data: 'name', name: 'name' },
                        { data: 'region', name: 'region' },
                        { data: 'created_at', name: 'created_at'},
                     ]
            });
         });


</script>
@stop
