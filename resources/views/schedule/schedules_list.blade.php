@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.Select2', true)

@section('content_header')

@stop

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <div class="card-title">List of schedules </div>
            </div>
            <div class="card-body">
                    <!-- For defining autocomplete -->
                        <label class="col-sm-2 col-form-label" for='selSize'>pls select schedules</label>
                        <div class="col-sm-10">
                          <select class='js-example-placeholder-single js-states form-control select2' id='selSize'>
                          </select>
                        </div>

              @include('schedule/scheduleevent_pivot')


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
          placeholder: "Select schedules...",
          multiple: true,
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('schedule.list_sel')}}",
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

      $('#selSize').on('select2:select select2:unselect', function(e) {
                var data = e.params.data;
                var values = $('#selSize').select2('data');
                var selVals = values.map(function(elem) {
                  return {
                    id: elem.id,
                    text: elem.text
                  };
                });
                if (selVals.length == 0){
                  selVals[0] = {};
                }
                console.log(selVals);
                var url = '{{ route("schedule_event.list-piv") }}';
                $.ajaxSetup({
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
                });
                $.ajax({
                  type: 'POST',
                  url: url,
                  data: {selVals},
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
