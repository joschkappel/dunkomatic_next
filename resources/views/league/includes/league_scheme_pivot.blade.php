
<div class="card-body" id="pivottable" >
@isset($scheme)
                    <table width="100%" class="table table-hover table-striped table-bordered table-sm" id="table">
                        <thead class="thead-light">
                          <tr>
                            @foreach ($scheme[0] as $key => $value)
                              <th class="text-center"><h6><span class="badge badge-pill badge-danger">@if ($key == 'game_day') @lang('schedule.game_day') @else {{ $key }} @endif</span></h6></th>
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

@endempty
</div>
