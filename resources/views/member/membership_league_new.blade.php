@extends('layouts.page')

@section('plugins.Select2', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-10">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('role.title.new', ['unittype'=> trans_choice('league.league',1), 'unitname'=> $league->shortname ])</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('league.membership.store',['league' => $league]) }}" method="POST">
                    <div class="card-body">
                        @method('POST')
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                           @lang('Please fix the following errors')
                        </div>
                        @endif

                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label" for='selRole'>{{trans_choice('role.role',1)}}</label>
                          <div class="col-sm-8">
                            <select class="js-placeholder-multi js-states form-control select2  @error('selRole') is-invalid @enderror" multiple="multiple" name="selRole[]" id='selRole'></select>
                            @error('selRole')
                            <div class="invalid-feedback">PLs select at least one Role</div>
                            @enderror
                          </div>
                        </div>
                        @include('member.includes.member_new')
                        <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function() {

      $(".js-placeholder-multi").select2({
          placeholder: "@lang('role.action.select')...",
          theme: 'bootstrap4',
          multiple: true,
          allowClear: false,
          minimumResultsForSearch: 10,
          ajax: {
                  url: "{{ route('role.index')}}",
                  type: "POST",
                  delay: 250,
                  dataType: "json",
                  data: {
                       "_token": "{{ csrf_token() }}",
                       "scope": 'LEAGUE'
                   },
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
      });

      function matchStart(params, data) {
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
          return data;
        }

        // Do not display the item if there is no 'text' property
        if (typeof data.text === 'undefined') {
          return null;
        }

        // `params.term` should be the term that is used for searching
        // `data.text` is the text that is displayed for the data object
        if (data.text.indexOf(params.term) > -1) {
          var modifiedData = $.extend({}, data, true);
          modifiedData.text += ' (matched)';

          // You can return modified objects from here
          // This includes matching the `children` how you want in nested data sets
          return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;

      }

      $(".js-placeholder-single").select2({
          placeholder: "@lang('role.member.action.select')...",
          theme: 'bootstrap4',
          multiple: false,
          allowClear: true,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('league.membership.index', ['language'=>app()->getLocale(), 'league' => $league->id]) }}",
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


      $('#selMember').on('select2:select', function(e) {
                var values = $('#selMember').select2('data');
                var selVals = values.map(function(elem) {
                  return {
                    id: elem.id,
                    text: elem.text
                  };
                });

                console.log(selVals);
                var url = "{{ route('member.show', ['language'=>app()->getLocale(), 'member'=>':member:'])}}";
                url = url.replace(':member:', selVals[0].id);
                $.ajax({
                  type: 'GET',
                  url: url,
                  success: function (data) {
                    $('#firstname').val(data.firstname);
                    $('#lastname').val(data.lastname);
                    $('#stree').val(data.stree);
                    $('#zipcode').val(data.zipcode);
                    $('#city').val(data.city);
                    $('#mobile').val(data.mobile);
                    $('#phone1').val(data.phone1);
                    $('#fax1').val(data.fax1);
                    $('#email1').val(data.email1);
                    $('#phone2').val(data.phone2);
                    $('#fax2').val(data.fax2);
                    $('#email2').val(data.email2);
                  },
                });
            });

      $('#selMember').on('select2:unselect select2:clear', function(e) {
        $('#firstname').val('');
        $('#lastname').val('');
        $('#stree').val('');
        $('#zipcode').val('');
        $('#city').val('');
        $('#mobile').val('');
        $('#phone1').val('');
        $('#fax1').val('');
        $('#email1').val('');
        $('#phone2').val('');
        $('#fax2').val('');
        $('#email2').val('');
      });

    });

</script>


@stop
