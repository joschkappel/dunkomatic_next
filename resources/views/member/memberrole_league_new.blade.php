@extends('adminlte::page')

@section('plugins.Select2', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-10">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">New roles for {{ $league->shortname }}</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('league.memberrole.store',['league' => $league]) }}" method="POST">
                    <div class="card-body">
                        @method('POST')
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Please fix the following errors
                        </div>
                        @endif

                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label" for='selRole'>pls select a role</label>

                          <div class="col-sm-10">
                            <select class="js-placeholder-multi js-states form-control select2  @error('selRole') is-invalid @enderror" multiple="multiple" name="selRole[]" id='selRole'></select>

                            @error('selRole')
                            <div class="invalid-feedback">PLs select at least one Role</div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="function" class="col-sm-2 col-form-label">Function</label>
                          <div class="col-sm-2">
                              <input type="text" class="form-control @error('function') is-invalid @enderror" id="function" name="function" placeholder="function" value="{{ old('function') }}">
                              @error('function')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label" for='selMember'>pls select a member</label>
                          <div class="col-sm-10">
                            <select class='js-placeholder-single js-states form-control select2' name="selMember" id='selMember'>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                            <label for="firstname" class="col-sm-2 col-form-label">Firstname</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" placeholder="firstname" value="{{ old('firstname') }}">
                                @error('firstname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="lastname" class="col-sm-2 col-form-label">Lastname</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" placeholder="lastname" value="{{ old('lastname') }}">
                                @error('lastname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="street" class="col-sm-2 col-form-label">Street</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" placeholder="street" value="{{ old('street')}}">
                                @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                          </div>
                        <div class="form-group row">
                            <label for="zipcode" class="col-sm-2 col-form-label">Zipcode</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control @error('zipcode') is-invalid @enderror" id="zipcode" name="zipcode" placeholder="zipcode" value="{{ old('zipcode') }}">
                                @error('zipcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="city" class="col-sm-2 col-form-label">City</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="city" value="{{old('city') }}">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mobile" class="col-sm-2 col-form-label">Mobile</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" placeholder="mobile phone no" value="{{ old('mobile') }}">
                                @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone1" class="col-sm-2 col-form-label">Phone</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('phone1') is-invalid @enderror" id="phone1" name="phone1" placeholder="main phone no" value="{{ old('phone1') }}">
                                @error('phone1')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="phone2" class="col-sm-2 col-form-label">Alt. Phone</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('phone2') is-invalid @enderror" id="phone2" name="phone2" placeholder="alternative phone no" value="{{ old('phone2') }}">
                                @error('phone2')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email1" class="col-sm-2 col-form-label">email</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('email1') is-invalid @enderror" id="email1" name="email1" placeholder="main email no" value="{{ old('email1') }}">
                                @error('email1')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="email2" class="col-sm-2 col-form-label">Alt. email</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('email2') is-invalid @enderror" id="email2" name="email2" placeholder="alternative email no" value="{{ old('email2') }}">
                                @error('email2')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fax1" class="col-sm-2 col-form-label">Fax</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('fax1') is-invalid @enderror" id="fax1" name="fax1" placeholder="main fax no" value="{{ old('fax1') }}">
                                @error('fax1')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="fax2" class="col-sm-2 col-form-label">Alt. Fax</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control @error('fax2') is-invalid @enderror" id="fax2" name="fax2" placeholder="alternative fax no" value="{{ old('fax2') }}">
                                @error('fax2')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info">Submit</button>
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
          placeholder: "Select roles...",
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
          placeholder: "Select member...",
          multiple: false,
          allowClear: true,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('league.memberrole.index', ['league' => $league->id]) }}",
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
                var url = "/member/"+selVals[0].id;
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
