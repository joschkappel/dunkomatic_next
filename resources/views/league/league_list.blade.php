@extends('page')

@section('content_header')
    @if(Session::has('success'))
    <div class="alert alert-success">
        {{Session::get('success')}}
    </div>
@endif
@stop

@section('plugins.Datatables', true)


@section('content')

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">@lang('league.title.list', ['region' => Auth::user()->region ])</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
                    <a href="{{ route('league.create', app()->getLocale()) }}" class="text-center btn btn-success btn-sm mb-3">@lang('league.action.create')</a>
                    <a href="{{ route('league.list_stats', app()->getLocale()) }}" class="text-center btn btn-success btn-sm mb-3">TESTING</a>

          </div>
          <div class="card-body">

         <table class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>@lang('league.shortname')</th>
                  <th>@lang('league.name')</th>
                  <th>@lang('club.region')</th>
                  <th>{{trans_choice('schedule.schedule',1)}}</th>
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
               ajax: '{{ route('league.list') }}',
               columns: [
                        { data: 'id', name: 'id', visible: false },
                        { data: 'shortname', name: 'shortname' },
                        { data: 'name', name: 'name' },
                        { data: 'region', name: 'region' },
                        { data: 'schedule.name', name: 'name', defaultContent: ''},
                        { data: 'created_at', name: 'created_at'},
                     ]
            });
         });


</script>
@stop
