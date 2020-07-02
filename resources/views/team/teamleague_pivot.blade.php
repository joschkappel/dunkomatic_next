<table class="table table-hover table-striped table-sm" id="pivottable">
    <tbody>
        @isset($plan)

        @foreach ($plan as $gd)
        <tr class="d-flex">
            @foreach ($gd as $key => $values)
            <td class="text-center col-2">
                @if ($values === ' ')<span></span>
                @elseif ($key === 'Game Date'){{ $values }}
                @else<span class="badge badge-pill badge-info">{{ $values }}</span>
                @endif
            </td>
            @endforeach
        </tr>
        @endforeach

        @endisset
        @empty($plan)
        <tr>
            <td>
                <div>
                    empty
                </div>
            </td>
        </tr>
        @endempty
    </tbody>
</table>
