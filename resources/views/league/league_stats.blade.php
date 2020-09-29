@extends('layouts.page')

@section('plugins.Datatables', true)

@section('content')

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">@lang('league.title.stats', ['region' => Auth::user()->region ])</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
          </div>
          <div class="card-body">

         <table class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>@lang('league.shortname')</th>
                  <th>@lang('league.name')</th>
                  <th>@lang('league.size')</th>
                  <th>@lang('club.entitled')</th>
                  <th>@lang('team.registered')</th>
                  <th>% {{__('Registered')}}</th>
                  <th>{{__('Total Games')}}</th>
                  <th>{{__('Games No Time')}}</th>
                  <th>{{__('Games No Teams')}}</th>
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
         ajax: '{{ route('league.list_stats', app()->getLocale()) }}',
         columns: [
                  { data: 'id', name: 'id', visible: false },
                  { data: 'shortname', name: 'shortname' },
                  { data: 'name', name: 'name' },
                  { data: 'schedule.size', name: 'size', defaultContent: ''},
                  { data: 'clubs_count', name: 'clubs_count'},
                  { data: 'teams_count', name: 'teams_count'},
                  { data: 'reg_rel', name: 'reg_rel'},
                  { data: 'games_count', name: 'games_count'},
                  { data: 'games_notime_count', name: 'games_notime_count'},
                  { data: 'games_noshow_count', name: 'games_noshow_count'},
               ]
      });
   });

</script>
@stop
