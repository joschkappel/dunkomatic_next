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
                <div class="card-title">List of league schemes </h3>
                    <!-- For defining autocomplete -->
                    <div>
                        <label for='selSize'>pls select a size</label>
                        <select class='js-example-placeholder-single js-states form-control select2' id='selSize'>
                        </select>
                    </div>
                </div>
              </div>
                  @include('league/league_scheme_pivot')

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

      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4',
      });

      $(".js-example-placeholder-single").select2({
          placeholder: "Select a size...",
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ url('size/index')}}",
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
                var url = '{{ url("scheme/:size:/list_piv") }}';
                url = url.replace(':size:', data.id);

                $.ajax({
                  type: 'GET',
                  url: url,
                  dataType: 'json',
                  success: function (data) {
                    $('.collapse').collapse('show');
                    $('#pivottable').html( data );
                  },


                });
      });
    });

</script>


@stop
