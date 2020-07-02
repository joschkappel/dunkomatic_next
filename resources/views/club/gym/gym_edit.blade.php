@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Modify gym {{ $gym->gym_no}} of club {{ $club->shortname}}</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('gym.update',['gym' => $gym]) }}" method="POST">
                    <div class="card-body">
                        <input type="hidden" name="_method" value="PUT">
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Please fix the following errors
                        </div>
                        @endif
                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">Number</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('gym_no') is-invalid @enderror" id="gym_no" name="gym_no" placeholder="Number" value="{{ old('gym_no', $gym->gym_no ) }}">
                                @error('gym_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ $gym->name }}">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">zip</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('zip') is-invalid @enderror" id="zip" name="zip" placeholder="Zipcode" value="{{ old('zip', $gym->zip) }}">
                                @error('zip')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">city</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="City" value="{{ $gym->city }}">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">Street</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" placeholder="Street" value="{{ $gym->street }}">
                                @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <a class="btn btn-default btn-close" href="{{url()->previous()}}">Cancel</a>
                                <button type="submit" class="btn btn-info">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- right column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Delete gym {{ $gym->gym_no}} of club {{ $club->shortname}}</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('gym.destroy',['gym' => $gym]) }}" method="POST">
                    <div class="card-body">
                        <input type="hidden" name="_method" value="DELETE">
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Please fix the following errors
                        </div>
                        @endif
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <a class="btn btn-default btn-close" href="{{url()->previous()}}">Cancel</a>
                                <button type="submit" class="btn btn-danger pull-right">Submit</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
