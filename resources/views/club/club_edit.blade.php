@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Modify club {{ $club->shortname}}</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('club.update',['club' => $club]) }}" method="POST">
                    <div class="card-body">
                        <input type="hidden" name="_method" value="PUT">
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Please fix the following errors
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="title" class="col-sm-2 col-form-label">region</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('region') is-invalid @enderror" id="region" name="region" placeholder="region" value="{{ $club->region}}">
                                @error('region')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-2 col-form-label">club_no</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('club_no') is-invalid @enderror" id="club_no" name="club_no" placeholder="club_no" value="{{ $club->club_no }}">
                                @error('club_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-2 col-form-label">shortname</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('shortname') is-invalid @enderror" id="shortname" name="shortname" placeholder="Shprtname" value="{{ $club->shortname }}">
                                @error('shortname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ $club->name }}">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="url" class="col-sm-2 col-form-label">Url</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" placeholder="URL" value="{{ $club->url }}">
                                @error('url')
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
