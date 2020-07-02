
<div class="card-body" id="pivottable" >
@isset($scheme)
                    <table class="table table-hover table-striped table-bordered table-sm" id="table">
                        <thead class="thead-light">
                          <tr>
                            @foreach ($scheme[0] as $key => $value)
                              <th class="text-center"><h6><span class="badge badge-pill badge-danger">{{ $key }}</span></h6></th>
                            @endforeach
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($scheme as $gd)
                              <tr>
                          @foreach ($gd as $key => $values)
                            <td class="text-center"><h6>@if ($values === ' ') @elseif ($key === 'game_day')<span class="badge badge-pill badge-dark">{{ $values }}</span>@else- <span class="badge badge-pill badge-info">{{ $values }}</span>@endif</h6></td>
                          @endforeach
                  </tr>
                @endforeach
              </tbody>
                    </table>
@endisset
@empty($scheme)
    pls select a size
@endempty
</div>
