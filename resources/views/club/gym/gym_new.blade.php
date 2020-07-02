@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Add new gym for club {{ $club->shortname}}</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('club.gym.store', ['club' => $club ]) }}" method="POST">
                    <div class="card-body">
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            Please fix the following errors
                        </div>
                        @endif
                        <input type="hidden" name="club_id" value="{{ $club->id}}">
                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">Number</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('gym_no') is-invalid @enderror" id="gym_no" name="gym_no" placeholder="Number" value="{{ old('gym_no')}}">
                                @error('gym_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ old('name')}}">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">zip</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('zip') is-invalid @enderror" id="zip" name="zip" placeholder="Zipcode" value="{{ old('zip')}}">
                                @error('zip')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">city</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="City" value="{{ old('city')}}">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="title" class="col-sm-2 col-form-label">Street</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" placeholder="Street" value="{{ old('street')}}">
                                @error('street')
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
