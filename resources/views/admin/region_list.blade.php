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
                    <h3 class="card-title">@lang('region.title.list')</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-tools p-2">
                    <a href="{{ route('region.create', app()->getLocale()) }}" class="text-center btn btn-success mb-3"><i class="fas fa-plus-circle"></i> @lang('region.action.create')</a>
          </div>
          <div class="card-body">

         <table width="100%" class="table table-hover table-bordered" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>@lang('region.code')</th>
                  <th>@lang('region.name')</th>
                  <th>@lang('region.hq')</th>
                  <th>{{ App\Enums\Role::RegionLead()->description  }}</th>
                  <th>{{ trans_choice('club.club',2)}}</th>
                  <th>{{ trans_choice('team.team',2)}}</th>
                  <th>{{ trans_choice('gym.gym',2)}}</th>
                  <th>{{ trans_choice('league.league',2)}}</th>
               </tr>
            </thead>
         </table>
          </div>
          <!-- /.card-body -->
        <!-- all modals here -->
        <!-- all modals above -->
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
               ajax: '{{ route('region.list.dt', ['language'=>app()->getLocale()]) }}',
               columns: [
                        { data: 'id', name: 'id', visible: false },
                        { data: 'code', name: 'code' },
                        { data: 'name', name: 'name' },
                        { data: 'hq', name: 'hq' },
                        { data: 'regionadmin', name: 'regionadmin' },
                        { data: 'clubs_count', name: 'clubs' },
                        { data: 'teams_count', name: 'teams' },
                        { data: 'gyms_count', name: 'gyms' },
                        { data: 'leagues_count', name: 'leagues' },
                     ]
            });
         });


</script>
@stop
