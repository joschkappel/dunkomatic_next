@extends('layouts.page')

@section('content')

<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Create a new club </h3>
                </div>
                <!-- /.card-header -->
                    <div class="card-body">
                        @for ($i = 0; $i < 10; $i++)
                          <div class="row">
                            @for ($j = 0; $j < 10; $j++)
                              <span class="badge badge-info">{{ $i.' '.$j }}</span>
                            @endfor
                          </div>
                        @endfor
                      </div>
                </div>
        <div class="card card-light">
            <div class="card-header">
                <h3 class="card-title">tabel test </h3>
            </div>
            <!-- /.card-header -->

            <div class="card-body">
              <table class="table table-hover table-bordered table-sm" id="table">
                  <thead>
                    <tr>
                      @foreach ($test[0] as $key => $value)
                        <th class="text-center"><h6><span class="badge badge-pill badge-danger">{{ $key }}</span></h6></th>
                      @endforeach
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($test as $gd)
                        <tr>
                    @foreach ($gd as $key => $values)
                      <td class="text-center"><h6>@if ($values === ' ') @elseif ($key === 'game_day')<span class="badge badge-pill badge-dark">{{ $values }}</span>@else- <span class="badge badge-pill badge-info">{{ $values }}</span>@endif</h6></td>
                    @endforeach
            </tr>
          @endforeach
        </tbody>
          </table>

        </div>
    </div>
</div>
</div>


@stop

@section('footer')
jochenk
@stop

@section('css')

@stop
