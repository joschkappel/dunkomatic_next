<div class="card card-outline card-danger collapsed-card">
    <div class="card-header">
        <h4 class="card-title font-weight-bold pt-2"><i class="fas fa-bell text-danger mx-2"></i>{{ __('message.reminder') }}</h4>
        <div class="card-tools">
            @if (count($reminders)!=null)
                <span class="badge badge-danger text-md">{{ count($reminders) }}</span>
            @endif
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
        </div>
        <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
            @forelse ($reminders as $r)
                <div class="col">
                    <div class="alert alert-{{$r['action_color']}} text-sm" role="alert">{!! $r['msg'] !!}
                    @if ($r['action'] != '')
                        <a href="{{ $r['action'] }}" class="alert-link text-sm">{!! $r['action_msg'] !!}</a>
                    @endif
                    </div>
                </div>
            @empty
                <div class="col">
                    <div class="alert alert-info" role="alert">{{__('message.reminder.empty')}}</div>
                </div>
            @endforelse
        <!-- The last icon means the story is complete -->
    </div>
</div>
