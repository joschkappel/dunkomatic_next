@extends('layouts.page')

@section('plugins.Datatables', true)

@section('content')

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">@lang('club.title.stats', ['region' =>  session('cur_region')->name ])</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
          </div>
          <div class="card-body">

         <table width="100%" class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>@lang('club.shortname')</th>
                  <th>@lang('club.name')</th>
                  <th>{{ trans_choice('team.team',2)}}</th>
                  <th>@lang('team.registered')</th>
                  <th>% {{__('Registered')}}</th>
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


@section('js')

<script>
   $(function() {
         $('#table').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         @if (app()->getLocale() == 'de')
         language: { "url": "{{URL::asset('vendor/datatables-plugins/i18n/German.json')}}" },
         @else
         language: { "url": "{{URL::asset('vendor/datatables-plugins/i18n/English.json')}}" },
         @endif
         order: [[1,'asc']],
         ajax: '{{ route('club.list_stats', ['region' => session('cur_region')->id]) }}',
         columns: [
                  { data: 'id', name: 'id', visible: false },
                  { data: 'shortname', name: 'shortname' },
                  { data: 'name', name: 'name' },
                  { data: 'teams_count', name: 'teams_count'},
                  { data: 'registered_teams_count', name: 'registere_teams_count'},
                  { data: 'reg_rel', name: 'reg_rel'},
                  { data: 'games_home_count', name: 'games_home_count'},
                  { data: 'games_home_notime_count', name: 'games_home_notime_count'},
                  { data: 'games_home_noshow_count', name: 'games_home_noshow_count'},
               ]
      });
   });

</script>
@stop
