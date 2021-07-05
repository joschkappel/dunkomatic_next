@extends('layouts.page')

@section('plugins.Datatables', true)

@section('content')

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">@lang('club.title.list', ['region' =>  session('cur_region')->name ])</h3>
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
                  <th>{{ __('containing')}}@lang('team.assigned')</th>
                  <th>{{ __('containing')}}@lang('team.registered')</th>
                  <th>{{ __('containing')}}@lang('team.selected')</th>
                  <th>{{ __('Total Games')}}</th>
                  <th>{{ __('Games No Time')}}</th>
                  <th>{{ __('Games No Teams')}}</th>
                  <th>{{__('Updated at')}}</th>
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
         ajax: '{{ route('club.list', ['region' => session('cur_region')->id]) }}',
         columns: [
                  { data: 'id', name: 'id', visible: false },
                  { data:  {
                     _: 'shortname.sort',
                     filter: 'shortname.sort',
                     display: 'shortname.display',
                     sort: 'shortname.sort'
                   }, name: 'shortname.sort' },
                  { data: {
                     _: 'name.sort',
                     filter: 'name.sort',
                     display: 'name.display',
                     sort: 'name.sort' 
                     }, name: 'name.sort' },
                  { data: 'teams_count', name: 'teams_count'},
                  { data: {
                     _: 'assigned_rel.sort',
                     filter: 'assigned_rel.sort',
                     display: 'assigned_rel.display',
                     sort: 'assigned_rel.sort' 
                     }, name: 'assigned_rel.sort' },
                  { data: {
                     _: 'registered_rel.sort',
                     filter: 'registered_rel.sort',
                     display: 'registered_rel.display',
                     sort: 'registered_rel.sort' 
                     }, name: 'registered_rel.sort' },
                  { data: {
                     _: 'selected_rel.sort',
                     filter: 'selected_rel.sort',
                     display: 'selected_rel.display',
                     sort: 'selected_rel.sort' 
                     }, name: 'selected_rel.sort' },                     
                  { data: 'games_home_count', name: 'games_home_count'},
                  { data: 'games_home_notime_count', name: 'games_home_notime_count'},
                  { data: 'games_home_noshow_count', name: 'games_home_noshow_count'},
                  { data: 'updated_at', name: 'updated_at'},
               ]
      });
   });

</script>
@stop
