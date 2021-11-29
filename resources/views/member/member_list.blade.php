@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('role.member.title.list', ['region'=>$region->name ]) }}">
    <th>Id</th>
    <th>Name</th>
    <th>{{__('role.email1')}}</th>
    <th>{{__('role.phone')}}</th>
    <th>{{ trans_choice('club.club',2)}}</th>
    <th>{{ trans_choice('league.league',2)}}</th>
    <th>{{ __('auth.user.account') }}</th>

</x-card-list>
@endsection

@section('js')

<script>
         $(function() {
              $('#goBack').click(function(e){
                  history.back();
              });
              $('#table').DataTable({
                 processing: true,
                 serverSide: true,
                 responsive: true,
                 language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                 order: [[1,'asc']],
                 ajax: '{{ route('member.datatable', ['region' => $region]) }}',
                 columns: [
                          { data: 'id', name: 'id', visible: false },
                          { data: 'name', name: 'name' },
                          { data: 'email1', name: 'email1' },
                          { data: 'phone', name: 'phone' },
                          { data: 'clubs', name: 'clubs' },
                          { data: 'leagues', name: 'leagues' },
                          { data: 'user_account', name: 'user_account' },
                       ]
              });
            });

</script>
@endsection
