<div class="card card-outline card-danger">
    <div class="card-header">
        <h4 class="card-title pt-2 font-weight-bold"><i class="fas fa-bell text-danger mx-2"></i>{{ __('message.reminder') }}</h4>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
            @forelse ($reminders as $r)
                <div class="col-sm-12">
                    <div class="alert alert-{{$r['action_color']}}" role="alert">{!! $r['msg'] !!}
                    @if ($r['action'] != '')
                        <a href="{{ $r['action'] }}" class="alert-link">{!! $r['action_msg'] !!}</a>
                    @endif
                    </div>
                </div>
            @empty
                <div class="col-sm-12">
                    <div class="alert alert-info" role="alert">{{__('message.reminder.empty')}}</div>
                </div>
            @endforelse
        <!-- The last icon means the story is complete -->
    </div>
</div>
