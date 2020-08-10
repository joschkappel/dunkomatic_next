@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.Select2', true)

@section('content_header')

@stop

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <div class="card-title">@lang('schedule.title.scheme.list')</h3>
                    <!-- For defining autocomplete -->
                    <div>
                        <label for='selSize'>@lang('schedule.action.size.select')</label>
                        <select class='js-example-placeholder-single js-states form-control select2' id='selSize'>
                        </select>
                    </div>
                </div>
              </div>
                  @include('league/includes/league_scheme_pivot')

            </div>
        </div>
    </div>
    </div>
@stop

@section('footer')
    jochenk
@stop


@section('js')
<script>
    $(function() {


      $(".js-example-placeholder-single").select2({
          placeholder: "@lang('schedule.action.size.select')...",
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('size.index')}}",
                  type: "get",
                  delay: 250,
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
      });

      $('#selSize').on('select2:select', function(e) {
                var data = e.params.data;
                var url = '{{ route("scheme.list_piv", ['size'=>":size:"]) }}';
                url = url.replace(':size:', data.id);

                $.ajax({
                  type: 'GET',
                  url: url,
                  success: function (data) {
                    $('.collapse').collapse('show');
                    $('#pivottable').html( data );
                  },


                });
      });
    });

</script>


@stop
