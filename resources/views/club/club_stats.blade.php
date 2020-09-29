@extends('page')

@section('css')
  <link type="text/css" rel="stylesheet" href="{{ URL::asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}" />
  <link type="text/css" rel="stylesheet" href="{{ URL::asset('vendor/datatables-plugins/responsive/css/responsive.bootstrap4.min.css') }}" />
@endsection


@section('content')

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">@lang('club.title.stats', ['region' => Auth::user()->region ])</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
          </div>
          <div class="card-body">

         <table class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>@lang('club.shortname')</th>
                  <th>Name</th>
                  <th>@lang('league.entitled')</th>
                  <th>@lang('team.registered')</th>
                  <th>{{ __('Total Games')}}</th>
                  <th>{{ __('Games No Time')}}</th>
                  <th>{{ __('Games No Teams')}}</th>
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
  <script src="{{ URL::asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ URL::asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ URL::asset('vendor/datatables-plugins/responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ URL::asset('vendor/datatables-plugins/responsive/js/responsive.bootstrap4.min.js') }}"></script>
  
<script>
   $(function() {
         $('#table').DataTable({
         processing: true,
         serverSide: true,
         order: [[1,'asc']],
         ajax: '{{ route('club.list_stats') }}',
         columns: [
                  { data: 'id', name: 'id', visible: false },
                  { data: 'shortname', name: 'shortname' },
                  { data: 'name', name: 'name' },
                  { data: 'leagues_count', name: 'leagues_count'},
                  { data: 'teams_count', name: 'teams_count'},
                  { data: 'games_home_count', name: 'games_home_count'},
                  { data: 'games_home_notime_count', name: 'games_home_notime_count'},
                  { data: 'games_home_noshow_count', name: 'games_home_noshow_count'},
               ]
      });
   });

</script>
@stop
