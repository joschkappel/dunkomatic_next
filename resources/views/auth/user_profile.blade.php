@extends('layouts.page')

@section('plugins.Select2', true)

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
                <div class="card-body">
                  <form class="form-horizontal" action="{{ route('admin.user.update', ['user' => Auth::user()]) }}" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        @csrf
                        @method('PUT')
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label">@lang('auth.full_name')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ Auth::user()->name }}">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label">@lang('auth.email')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ Auth::user()->email }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                      </form>
                  </div>
            </div>
          </div>
          @include('member.includes.member_edit')
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $("#updateMember").collapse("toggle");
</script>
@stop
