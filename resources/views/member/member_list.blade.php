@extends('layouts.page')

@section('content')
<x-card-list cardTitle="{{ __('role.member.title.list', ['region'=>$region->name ]) }}">
    <th>Id</th>
    <th>Name</th>
    <th>{{__('role.email1')}}</th>
    <th>{{__('role.email2')}}</th>
    <th>{{__('role.phone')}}</th>
    <th>{{ trans_choice('club.club',2)}}</th>
    <th>{{ trans_choice('league.league',2)}}</th>
    <th>{{ trans_choice('role.role',2) }}</th>
    <th class="noexport">{{ __('auth.user.account') }}</th>

</x-card-list>
@endsection

@section('js')
<script>
         $(function() {
              $('#goBack').click(function(e){
                  history.back();
              });


              var memtable = $('#table').DataTable({
                 processing: false,
                 responsive: true,
                 stateSave: true,
                 pageLength: {{ config('dunkomatic.table_page_length', 50)}},
                 language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                 order: [[1,'asc']],
                 ajax: "{{ route('member.datatable', ['region' => $region]) }}",
                 buttons: [
                     { extend: 'collection',
                       text: 'Export',
                       buttons: [
                            { extend: 'excelHtml5',
                                name: 'excel',
                                exportOptions: { orthogonal: 'export', columns: ':visible' },
                                title: '{{ __('role.member.title.list', ['region'=> $region->name ]) }}',
                                messageTop: function () {
                                    var filter = $('.dataTables_filter input').val();
                                    if (filter != ''){
                                        return '{{ __('reports.filtered')}}'+filter;
                                    }
                                }
                            },
                            { extend: 'pdfHtml5',
                                title: '{{ __('role.member.title.list', ['region'=> $region->name ]) }}',
                                exportOptions: { orthogonal: 'export', columns: ':visible' },
                                messageTop: function () {
                                    var filter = $('.dataTables_filter input').val();
                                    if (filter != ''){
                                        return '{{ __('reports.filtered')}}'+filter;
                                    }
                                }
                            },
                            { extend: 'csv',
                                exportOptions: { orthogonal: 'export', columns: ':visible' },
                                name: 'csv',
                            },
                        ]
                     },
                    { extend: 'spacer',
                        style: 'bar'
                    },
                    { extend: 'print',
                        name: 'print',
                        title: '{{ __('role.member.title.list', ['region'=> $region->name ]) }}',
                        exportOptions: { orthogonal: 'export', columns: ':visible' },
                        messageTop: function () {
                            var filter = $('.dataTables_filter input').val();
                            if (filter != ''){
                                 return '{{ __('reports.filtered')}}'+filter;
                            }
                        },
                    },
                    { extend: 'copy',
                        exportOptions: { columns: ':visible' },
                        messageTop: function () {
                            var filter = $('.dataTables_filter input').val();
                            if (filter != ''){
                                 return '{{ __('reports.filtered')}}'+filter;
                            }
                        },
                    },
                    { extend: 'spacer',
                        style: 'bar'
                    },
                    { extend: 'colvis',
                        postfixButtons: [ 'colvisRestore' ],
                        columns: [1,2,3,4,5,6,7],
                    },
                    { extend: 'spacer',
                        style: 'bar'
                    },
                    { extend: 'pageLength'
                    }


                ],
                 columns: [
                          { data: 'id', name: 'id', visible: false },
                          { data: 'name', name: 'name' },
                          { data: 'email1', name: 'email1' },
                          { data: 'email2', name: 'email2' },
                          { data: 'phone', name: 'phone' },
                          { data: 'clubs', name: 'clubs' },
                          { data: 'leagues', name: 'leagues' },
                          { data: 'roles', name: 'roles' },
                          { data: 'user_account', name: 'user_account' },
                       ],
                dom: 'Brftip'
              });

/*               $('#table').on('search.dt', function() {
                    var search_value = $('.dataTables_filter input').val();
                    $('#table').DataTable().buttons('print:name');
                    console.log(value); // <-- the value
              }); */

              $(document).on('click', '#copyAddress', function () {
               var url = "{{ route('member.show', [ 'language'=>app()->getLocale(), 'member'=>':memberid:'])}}"
               url = url.replace(':memberid:',$(this).data('member-id') );
               $.ajax( {
                       url: url,
                       type: "get",
                       dataType: 'json',
                       data: {
                         _token: "{{ csrf_token() }}",
                         _method: 'GET'
                       },
                       delay: 250,
                       success: function (response) {
                            let adr = response['firstname']+' '+response['lastname'];
                            adr += '\n'+response['street'];
                            adr += '\n'+response['zipcode']+' '+response['city'];

                            if (navigator.clipboard && window.isSecureContext) {
                                // navigator clipboard api method'
                                return navigator.clipboard.writeText(adr);
                            } else {
                                // text area method
                                let textArea = document.createElement("textarea");

                                textArea.value = adr;
                                // make the textarea out of viewport
                                textArea.style.position = "fixed";
                                textArea.style.left = "-999999px";
                                textArea.style.top = "-999999px";
                                document.body.appendChild(textArea);
                                textArea.focus();
                                textArea.select();
                                document.execCommand('copy');
                                textArea.remove();
                            }
                       },
                       cache: false
                     });
                });

            });

</script>
@endsection
