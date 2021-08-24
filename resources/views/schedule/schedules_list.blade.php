@extends('layouts.page')

@section('plugins.Datatables', true)
@section('plugins.Select2', true)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">@lang('schedule.title.compare', ['region' => session('cur_region')->name ])</h3>
                    </div>
                    <div class="card-body">
                        <!-- For defining autocomplete -->
                        <label class="col-sm-2 col-form-label"
                            for='selSize'>{{ trans_choice('schedule.schedule', 2) }}</label>
                        <div class="col-sm-10">
                        <div class="input-group mb-3">
                            <select class='js-size-multiple js-states form-control select2' id='selSize'>
                            </select>
                            </div>
                        </div>

                        @include('schedule/includes/scheduleevent_pivot')


                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="btn-toolbar justify-content-end" role="toolbar" aria-label="Toolbar with button groups">
                            <button type="button" class="btn btn-outline-primary mr-2" id="goBack" data-dismiss="modal">{{ __('Cancel')}}</button>
                        </div>
                    </div>
                    <!-- /.card-footer -->                    
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function() {
            $('#goBack').click(function(e){
                history.back();
            });
            $(".js-size-multiple").select2({
                placeholder: "@lang('schedule.action.select')...",
                theme: 'bootstrap4',
                multiple: true,
                allowClear: false,
                minimumResultsForSearch: -1,
                ajax: {
                    url: "{{ route('schedule.sb.region', ['region' => session('cur_region')->id]) }}",
                    type: "get",
                    delay: 250,
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });

            $('#selSize').on('select2:select select2:unselect', function(e) {
                var data = e.params.data;
                var values = $('#selSize').select2('data');
                var selVals = values.map(function(elem) {
                    return {
                        id: elem.id,
                        text: elem.text
                    };
                });
                if (selVals.length == 0) {
                    selVals[0] = {};
                }
                console.log(selVals);
                var url = '{{ route('schedule_event.list-piv') }}';
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        selVals
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('.collapse').collapse('show');
                        $('#pivottable').html(data);
                    },
                });
            });

        });
    </script>


@stop
