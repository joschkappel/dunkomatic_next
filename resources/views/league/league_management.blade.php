@extends('layouts.page')

@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.Toastr', true)
@section('plugins.Duallistbox', true)

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="collapse" id="collapseAssignment">

                <div class="card card-info">
                    <h5 class="card-header">Assign Clubs</h5>
                    <form id="frmAssignClubs" action="#" method="post">

                        <div class="card-body">

                            @csrf
                            @method('POST')
                            <select multiple="multiple" id="clubsduallistbox" name="assignedClubs[]">
                            </select>
                            <br>
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
            <div class="card card-secondary">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @lang('Please fix the following errors')
                        <ul>
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <h4 class="card-header">@lang('league.title.management')</h4>
                
                <!-- /.card-header -->

                <div class="card-tools p-2">
                    <a href="{{ route('league.create', app()->getLocale()) }}" class="text-center btn btn-success mb-3"><i
                            class="fas fa-plus-circle pr-2"></i>@lang('league.action.create')</a>
                </div>
                <div class="card-body">
                    <table width="100%" class="table table-hover table-bordered table-sm" id="tblAssignClubs">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>@lang('league.shortname')</th>
                                <th>@lang('league.agetype')</th>
                                <th>@lang('league.gendertype')</th>
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
            toastr.options.closeButton = true;
            toastr.options.closeMethod = 'fadeOut';
            toastr.options.closeDuration = 60;
            toastr.options.closeEasing = 'swing';
            toastr.options.progressBar = true;
            //var demo1 = $('select[name="duallistbox_demo1[]"]').bootstrapDualListbox();


            $('#tblAssignClubs').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                @if (app()->getLocale() == 'de')
                    language: { "url": "{{ URL::asset('vendor/datatables-plugins/i18n/German.json') }}" },
                @else
                    language: { "url": "{{ URL::asset('vendor/datatables-plugins/i18n/English.json') }}" },
                @endif
                order: [
                    [1, 'asc']
                ],
                ajax: '{{ route('league.club_assign.dt', ['language'=> app()->getLocale(),'region' => session('cur_region')->id]) }}',
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false
                    },
                    { data:  {
                        _: 'shortname.sort',
                        filter: 'shortname.sort',
                        display: 'shortname.display',
                        sort: 'shortname.sort'
                    }, name: 'shortname.sort' },
                    { data: {
                        _: 'age_type.display',
                        filter: 'age_type.display',
                        display: 'age_type.display',
                        sort: 'age_type.sort'
                        }, name: 'age_type.display'
                    },
                    { data: {
                        _: 'gender_type.display',
                        filter: 'gender_type.display',
                        display: 'gender_type.display',
                        sort: 'gender_type.sort'
                        }, name: 'gender_type.display'
                    },
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
