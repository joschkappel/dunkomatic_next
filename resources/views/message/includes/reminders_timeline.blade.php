<div class="card card-outline card-danger">
    <div class="card-header">
        <h4 class="card-title pt-2 font-weight-bold"><i class="fas fa-bell text-danger mx-2"></i>{{ __('message.reminder') }}</h4>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
            <ul class="list-group list-group-flush">
            @forelse ($reminders as $r)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    @if ($r['action'] != '')
                    <a href="{{ $r['action'] }}" class="list-group-item list-group-item-action @if ($loop->first) active @endif rounded">{{ $r['msg'] }}</a>
                    @else
                    {{ $r['msg'] }}
                    @endif
                </li>
            @empty
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ __('message.reminder.empty')}}
                </li>
            @endforelse
            </ul>
        <!-- The last icon means the story is complete -->
    </div>
</div>
