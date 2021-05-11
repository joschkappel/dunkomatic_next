@extends('layouts.page')

@section('plugins.Datatables', true)

@section('content')

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">@lang('role.member.title.list', ['region'=>session('cur_region')->name ])</h3>
                  </div>
                  <!-- /.card-header -->
          <div class="card-body">
            @csrf

         <table width="100%" class="table table-hover table-bordered table-sm" id="table">
            <thead class="thead-light">
               <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>{{ trans_choice('club.club',2)}}</th>
                  <th>{{ trans_choice('league.league',2)}}</th>
                  <th>{{ __('auth.user.account') }}</th>
                  <th>{{__('Created at')}}</th>
                  <th>{{__('Updated at')}}</th>
               </tr>
            </thead>
         </table>
          </div>

        </div>
        <!-- /.card-body -->
        <!-- all modals here -->

        <!-- all modals above -->
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
                 ajax: '{{ route('member.datatable', ['region' => session('cur_region')->id]) }}',
                 columns: [
                          { data: 'id', name: 'id', visible: false },
                          { data: 'name', name: 'name' },
                          { data: 'clubs', name: 'clubs' },
                          { data: 'leagues', name: 'leagues' },
                          { data: 'user_account', name: 'user_account' },
                          { data: 'created_at', name: 'created_at'},
                          { data: 'updated_at', name: 'updated_at'},
                       ]
              });
            });

</script>
@stop
