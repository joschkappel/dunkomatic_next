<div class="card-body" id="pivottable">
    @isset($events)
    <table class="table table-hover table-striped table-bordered table-sm" id="table">
        <thead class="thead-light">
            <tr>
                @foreach ($events[0] as $key => $value)
                <th class="text-center">
                    <h6>{{ $key }}</h6>
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $gd)
            <tr>
                @foreach ($gd as $key => $values)
                <td class="text-center">
                    <h5>
                        @if ($values === ' ')
                        @elseif ($key === 'Game Date'){{ $values }}
                        @else<span class="badge badge-pill badge-info">{{ $values }}</span>
                        @endif
                    </h5>
                </td> 
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @endisset
    @empty($events)
    pls select a schedule
    @endempty
</div>
