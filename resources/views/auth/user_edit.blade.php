@extends('layouts.page')

@section('plugins.Select2', true)
@section('plugins.ICheck', true)


@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('auth.title.edit')</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('admin.user.allowance', ['language'=>app()->getLocale(), 'user'=>$user]) }}" method="post">
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 col-form-label">@lang('auth.full_name')</label>
                            <div class="col-sm-6">
                                <input type="input" readonly class="form-control" id="name" value="{{ $user->name}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-4 col-form-label">@lang('auth.email')</label>
                            <div class="col-sm-6">
                                <input type="input" readonly class="form-control" id="email" value="{{ $user->email}}">
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for='selClubs' class="col-sm-4 col-form-label">{{ trans_choice('club.club',2)}}</label>
                            <div class="col-sm-6">
                                <select class='js-clubs-placeholder-single js-states form-control select2 @error('club_ids') /> is-invalid @enderror' multiple="multiple"  id='selClubs' name="club_ids[]">
                                  @foreach ( $user['clubs'] as $k=>$v )
                                       <option value="{{ $v }}" selected>{{ $k }}</option>
                                  @endforeach
                                </select>
                                @error('club_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for='selLeagues' class="col-sm-4 col-form-label">{{ trans_choice('league.league',2)}}</label>
                            <div class="col-sm-6">
                                <select class='js-leagues-placeholder-single js-states form-control select2 @error('league_ids') /> is-invalid @enderror' multiple="multiple" id='selLeagues' name="league_ids[]">
                                  @foreach ($user['leagues'] as $k=>$v)
                                    <option value="{{$v}}" selected>{{ $k }}</option>
                                  @endforeach
                                </select>
                                @error('league_ids')
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

@section('js')
<script>
    $(function() {
        $("#selClubs").select2({
            placeholder: "@lang('club.action.select')...",
            theme: 'bootstrap4',
            multiple: true,
            allowClear: false,
            minimumResultsForSearch: 20,
            ajax: {
                    url: "{{ route('club.sb.region', ['region'=>$user->region->id])}}",
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
            theme: 'bootstrap4',
            multiple: true,
            allowClear: false,
            minimumResultsForSearch: 20,
            ajax: {
                    url: "{{ route('league.sb.region', ['region'=>$user->region->id])}}",
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
