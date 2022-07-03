@extends('layouts.page')

@section('content_header')

@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col col-10">
                <div class="card card-secondary">
                    <h4 class="card-header">@lang('schedule.title.scheme.list')</h4>
                    <!-- For defining autocomplete -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <div>
                                    <label for='selSize'>@lang('schedule.action.size.select')</label>
                                    <select class='js-example-placeholder-single js-states form-control select2'
                                        id='selSize'>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="btn-toolbar justify-content-end" role="toolbar" aria-label="Toolbar with button groups">
                            <button type="button" class="btn btn-outline-secondary mr-2" id="getHelp">{{ __('Help')}}</button>
                            <button type="button" class="btn btn-outline-primary mr-2" id="goBack">{{ __('Cancel')}}</button>
                        </div>
                    </div>
                    <!-- /.card-footer -->

                </div>
                @include('league/includes/league_scheme_pivot')
                @include('league.includes.league_scheme_list_help')

            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function() {
            $(document).on('click', 'button#getHelp', function() {
                $('#modalLeagueSchemeListHelp_{{app()->getLocale()}}').modal('show');
            });
            $('#goBack').click(function(e){
                history.back();
            });

            $(".js-example-placeholder-single").select2({
                placeholder: "@lang('schedule.action.size.select')...",
                width: '100%',
                allowClear: false,
                minimumResultsForSearch: -1,
                ajax: {
                    url: "{{ route('size.index') }}",
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

            $('#selSize').on('select2:select', function(e) {
                var data = e.params.data;
                var url = '{{ route('scheme.list_piv', ['size' => ':size:']) }}';
                url = url.replace(':size:', data.id);

                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        $('.collapse').collapse('show');
                        $('#pivottable').html(data);
                    },


                });
            });
        });
    </script>


@stop
