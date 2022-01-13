@extends('layouts.page')

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="collapse" id="collapseAssignment">

                <div class="card card-outline card-primary">
                    <h5 class="card-header">{{ __('league.action.open.assignment')}}</h5>
                    <form id="frmAssignClubs" action="#" method="post">

                        <div class="card-body">

                            @csrf
                            @method('POST')
                            <div class="input-group mb-3">
                            <select multiple="multiple" id="clubsduallistbox" name="assignedClubs[]">
                            </select>
                            <br>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar"  aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                                <button type="button" data-toggle="collapse" data-target="#collapseAssignment"
                                    class="btn btn-secondary">@lang('Cancel')</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">@lang('league.title.management')</h3>
                    <div class="card-tools">
                        <span>
                            @can('create-leagues')
                            <a href="{{ route('league.create', ['language'=>app()->getLocale(), 'region'=>$region]) }}" class="btn btn-success"><i
                                class="fas fa-plus-circle pr-2"></i>@lang('league.action.create')</a>
                            @endcan
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <table width="100%" class="table table-hover table-bordered table-sm" id="tblAssignClubs">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                @if ($region->is_base_level)
                                    <th>{{ trans_choice('region.region',1) }}</th>
                                @endif
                                <th>@lang('league.shortname')</th>
                                <th>@lang('league.state')</th>
                                <th>@lang('club.entitled')</th>
                                <th>@lang('club.registered')</th>
                                <th>@lang('league.next.state')</th>
                                <th>@lang('league.prev.state')</th>
                            </tr>
                        </thead>
                    </table>


                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                </div>
                <!-- /.card-footer -->
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function() {

            //var demo1 = $('select[name="duallistbox_demo1[]"]').bootstrapDualListbox();


            $('#tblAssignClubs').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                language: { "url": "{{URL::asset('lang/vendor/datatables.net/'.app()->getLocale().'.json')}}" },
                order: [
                    [1, 'asc']
                ],
                ajax: '{{ route('league.list_mgmt', ['language'=> app()->getLocale(),'region' => $region]) }}',
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false
                    },
                    @if ($region->is_base_level)
                        { data: 'alien_region', name: 'alien_region'},
                    @endif
                    { data:  {
                        _: 'shortname.sort',
                        filter: 'shortname.sort',
                        display: 'shortname.display',
                        sort: 'shortname.sort'
                    }, name: 'shortname.sort' },
                    { data: 'state', name: 'state', width: '1%'},
                    {
                        data: 'clubs',
                        name: 'clubs'
                    },
                    {
                        data: 'teams',
                        name: 'teams'
                    },
                    {
                        data: 'nextaction',
                        name: 'nextaction'
                    },
                    {
                        data: 'rollbackaction',
                        name: 'rollbackaction'
                    },
                ]
            });

            $(document).on('click', 'button#assignClub', function() {

                var leagueid = $(this).data("league");
                var url = "{{ route('league.sb.club', ['league' => ':leagueid:']) }}"
                url = url.replace(':leagueid:', leagueid);
                var url2 = "{{ route('league.assign-clubs', ['league' => ':leagueid:']) }}";
                url2 = url2.replace(':leagueid:', leagueid);
                $('#frmAssignClubs').attr('action', url2);

                $.ajax({
                    type: "GET",
                    dataType: 'json',
                    url: url,
                    success: function(data) {
                        var selector = $('#clubsduallistbox')[0];
                        if (typeof(selector) != "undefined") {
                            $('#clubsduallistbox').find('option').remove().end();
                            $('#clubsduallistbox').bootstrapDualListbox('refresh', true);
                        }

                        var objs = data;
                        $(objs).each(function() {

                            var o = document.createElement("option");
                            o.value = this['id'];
                            o.text = this['text'];
                            if (this['selected']) {
                                o.selected = 'selected';
                            }
                            if (typeof(selector) != "undefined") {
                                selector.options.add(o);
                            }
                        });
                        //Render dualListbox
                        $('#clubsduallistbox').bootstrapDualListbox({
                            moveOnSelect: false,
                            nonSelectedListLabel: 'Alle Vereine',
                            selectedListLabel: 'davon Zugeordnet',
                            selectorMinimalHeight: 250,
                        });
                        $('#clubsduallistbox').bootstrapDualListbox('refresh', true);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });
            $(document).on('click', 'button#changeState', function() {
                var leagueid = $(this).data("league");
                var action = $(this).data('action');
                var url = "{{ route('league.state.change', ['league' => ':leagueid:']) }}";
                url = url.replace(':leagueid:', leagueid);

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        action: action
                    },
                    url: url,
                    success: function(data) {
                        location.reload()
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });
              $(document).on('click', "button#createGames", function() {
                    var leagueid = $(this).data("league");
                    var url = "{{ route('league.game.store', ['league' => ':leagueid:']) }}";
                    url = url.replace(':leagueid:', leagueid);
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        url: url,
                        success: function(data) {
                            location.reload()
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });
                $(document).on('click', "button#deleteGames", function() {
                    var leagueid = $(this).data("league");
                    var url = "{{ route('league.game.destroy', ['league' => ':leagueid:']) }}";
                    url = url.replace(':leagueid:', leagueid);
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {
                            _method: "DELETE",
                            _token: "{{ csrf_token() }}"
                        },
                        url: url,
                        success: function(data) {
                            location.reload()
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                });
        });
    </script>
@stop
