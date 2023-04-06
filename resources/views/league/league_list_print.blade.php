@extends('layouts.page')
@section('css')
<style>
@media print{
    @page {
        size: landscape;
    }
}
</style>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">@lang('league.title.print',['region' => $region->name])</h3>
                </div>
                <div class="card-body">
                    <h5>{{App\Enums\LeagueAgeType::Senior()->description}}</h5>
                    <table width="100%" class="table table-hover table-bordered table-sm display compact " id="tableSeniors">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                @foreach($region->leagues->where('age_type', App\Enums\LeagueAgeType::Senior())->sortBy(['gender_type','name']) as $l)
                                <th>{{$l->shortname}}</th>
                                @endforeach
                            </tr>
                        </thead>
                    </table>
                <hr/>
                <h5>{{App\Enums\LeagueAgeType::Junior()->description}}</h5>
                    <table width="100%" class="table table-hover table-bordered table-sm display compact " id="tableJuniors">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                @foreach($region->leagues->where('age_type', '!=',  App\Enums\LeagueAgeType::Senior())->sortBy(['name']) as $l)
                                <th>{{$l->shortname}}</th>
                                @endforeach
                            </tr>
                        </thead>
                    </table>


                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="btn-toolbar justify-content-end" role="toolbar" aria-label="Toolbar with button groups">
                        <button type="button" class="btn btn-outline-primary mr-2" id="goBack">{{ __('Cancel')}}</button>
                    </div>
                </div>
                <!-- /.card-footer -->
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function() {

            var senTable = $('#tableSeniors').DataTable({
                processing: true,
                serverSide: false,
                scrollX: true,
                language: { url: "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                ajax: {
                    url: "{{ route('league.list_print',['language'=>app()->getLocale(), 'region'=>$region, 'type'=>'senior']) }}",
                },
                columns: [
                    { data: 'league_no', name: 'league_no' },
                    @foreach($region->leagues->where('age_type', App\Enums\LeagueAgeType::Senior()) as $l)
                        { data: '{{$l->shortname}}', name: '{{$l->shortname}}' },
                    @endforeach
                ],
                columnDefs: [
                    {
                        targets: '_all',
                        defaultContent: ' '
                    }
                ],
                dom: 'Brti',
                pageLength: 20,
                buttons: [
                    { extend: 'excelHtml5',
                        text: 'Excel',
                        filename: "{{$region->code}}_{{ __('reports.league.list') }}",
                        title: null,
                        sheetName: "{{ __('leage.title.list',['region'=>$region->shortcode])}}",
                    },
                    { extend: 'print',
                        orientation: 'portrait'
                    },
                    'copy'
                ]
            });
            var junTable = $('#tableJuniors').DataTable({
                processing: true,
                serverSide: false,
                scrollX: true,
                language: { url: "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                ajax: {
                    url: "{{ route('league.list_print',['language'=>app()->getLocale(), 'region'=>$region, 'type'=>'junior']) }}",
                },
                columns: [
                    { data: 'league_no', name: 'league_no' },
                    @foreach($region->leagues->where('age_type', '!=', App\Enums\LeagueAgeType::Senior()) as $l)
                        { data: '{{$l->shortname}}', name: '{{$l->shortname}}' },
                    @endforeach
                ],
                columnDefs: [
                    {
                        targets: '_all',
                        defaultContent: ' '
                    }
                ],
                dom: 'Brti',
                pageLength: 20,
                buttons: [
                    { extend: 'excelHtml5',
                        text: 'Excel',
                        filename: "{{$region->code}}_{{ __('reports.league.list') }}",
                        title: null,
                        sheetName: "{{ __('leage.title.list',['region'=>$region->shortcode])}}",
                    },
                    { extend: 'print',
                        orientation: 'portrait'
                    },
                    'copy'
                ]
            });

            $('#goBack').click(function(e){
                history.back();
            });

        });
    </script>
@stop
