@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-10">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">@lang('club.title.gamehome.import', ['club'=>$club->shortname])</h3>
                </div>
                <!-- /.card-header -->
                <form class="form-horizontal" action="{{ route('club.import.homegame',['language'=> app()->getLocale(),'club' => $club]) }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @lang('Please fix the following errors')
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="gfile" class="col-sm-4 col-form-label">Chose a file to upload</label>
                            <div class="col-sm-6">
                                <input type="file" class="form-control-file" accept=".xlsx,application/msexcel" id="gfile" name="gfile" ></input>
                            </div>
                        </div>
                        @if ($errors->any())
                        <div class="form-group row">
                            <div class="col-sm-10">
                            @foreach ($errors->all() as $message)
                              <div class="text-danger">{{ $message }}</div>
                            @endforeach
                          </div>
                      </div>
                        @endif
                        <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
