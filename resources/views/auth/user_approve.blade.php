@extends('page')

@section('css')
  <!-- iCheck for checkboxes and radio inputs -->
<link href="{{ URL::asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
@endsection
@section('plugins.Select2', true)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('user.title.approve')</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('admin.user.approve', ['language'=>app()->getLocale(), 'user_id'=>$user->id]) }}" method="post">
                    <div class="card-body">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="user_id" value="{{ $user->id}}">
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 col-form-label">@lang('user.name')</label>
                            <div class="col-sm-6">
                                <input type="input" readonly class="form-control" id="name" value="{{ $user->name}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 col-form-label">@lang('user.email')</label>
                            <div class="col-sm-6">
                                <input type="input" readonly class="form-control" id="email" value="{{ $user->email}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 col-form-label">@lang('user.reason_join')</label>
                            <div class="col-sm-6">
                                <input type="input" readonly class="form-control" id="reason_join" value="{{ $user->reason_join}}">
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for='selClubs' class="col-sm-4 col-form-label">{{ trans_choice('club.club',2)}}</label>
                            <div class="col-sm-6">
                                <select class='js-clubs-placeholder-single js-states form-control select2 @error('club_ids') /> is-invalid @enderror' id='selClubs' name="club_ids[]">
                                </select>
                                @error('club_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for='selLeagues' class="col-sm-4 col-form-label">{{ trans_choice('league.league',2)}}</label>
                            <div class="col-sm-6">
                                <select class='js-leagues-placeholder-single js-states form-control select2 @error('league_ids') /> is-invalid @enderror' id='selLeagues' name="league_ids[]">
                                </select>
                                @error('league_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label"></label>
                          <div class="icheck-success icheck-inline ">
                            <input type="checkbox" id="approved" name="approved" @if (old('approved') == 'on') checked @endif>
                            <label for="approved">{{ __('Approved')}} ?</label>
                          </div>
                        </div>
                        <div class="form-group row">
                            <label for="reason_reject" class="col-sm-4 col-form-label">@lang('user.reason_reject')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('reason_reject') is-invalid @enderror" id="reason_reject" name="reason_reject" placeholder="@lang('user.reason_reject')" value="{{ old('reason_reject') }}">
                                @error('reason_reject')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
jochenk
@stop

@section('js')
<script>
    $(function() {
        $("#selClubs").select2({
            placeholder: "@lang('club.action.select')...",
            multiple: true,
            allowClear: false,
            minimumResultsForSearch: 20,
            ajax: {
                    url: "{{ route('club.sb.region')}}",
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
        $("#selLeagues").select2({
            placeholder: "@lang('league.action.select')...",
            multiple: true,
            allowClear: false,
            minimumResultsForSearch: 20,
            ajax: {
                    url: "{{ route('league.sb.region')}}",
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
    });


</script>


@stop
