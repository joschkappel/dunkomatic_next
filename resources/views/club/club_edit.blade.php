@extends('layouts.page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('club.title.edit', ['club'=>$club->shortname])</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('club.update',['language'=>app()->getLocale(),'club' => $club]) }}" method="POST">
                    <div class="card-body">
                        <input type="hidden" name="_method" value="PUT">
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="region" class="col-sm-4 col-form-label">@lang('club.region')</label>
                            <div class="col-sm-6">
                                <input type="text" readonly class="form-control @error('region') is-invalid @enderror" id="region" name="region" placeholder="@lang('club.region')" value="{{ $club->region->code}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="club_no" class="col-sm-4 col-form-label">@lang('club.club_no')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('club_no') is-invalid @enderror" id="club_no" name="club_no" placeholder="@lang('club.club_no')" value="{{  (old('club_no')!='') ? old('club_no') : $club->club_no }}">
                                @error('club_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="shortname" class="col-sm-4 col-form-label">@lang('club.shortname')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('shortname') is-invalid @enderror" id="shortname" name="shortname" placeholder="@lang('club.shortname')" value="{{  (old('shortname')!='') ? old('shortname') : $club->shortname }}">
                                @error('shortname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="name" class="col-sm-4 col-form-label">@lang('club.name')</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="@lang('club.name')" value="{{  (old('name')!='') ? old('name') : $club->name }}">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="url" class="col-sm-4 col-form-label">URL</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" placeholder="URL" value="{{  (old('url')!='') ? old('url') : $club->url }}">
                                @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
