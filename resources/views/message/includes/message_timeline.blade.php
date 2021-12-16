<div class="card card-outline card-success">
    <div class="card-header">
        <h4 class="card-title font-weight-bold pt-2"><i class="fas fa-envelope text-success mx-2"></i>{{ trans_choice('message.message',2) }}</h4>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
<!-- Main node for this component -->
  <div class="timeline">
    <div class="time-label">
    @empty ($msglist)
        <span class="bg-info">{{__('message.message.empty')}}</span>
    @else
        <span class="bg-success">{{__('Eingang am')}}</span>
    @endempty
    </div>
    @foreach ( $msglist as $msgdate)
    <!-- Timeline time label -->
    <div class="time-label">
      <span class="bg-green">{{ \Carbon\CarbonImmutable::parse($msgdate['valid_from'])->locale( app()->getLocale() )->isoFormat('ll') }}</span>
    </div>
        @foreach ( $msgdate['items'] as $msg )
            <div>
                <!-- Before each timeline item corresponds to one icon on the left scale -->
                <i class="fas fa-envelope bg-blue "></i>
                <!-- Timeline item -->
                <div class="timeline-item">
                <!-- Time -->
                    <span class="time"><i class="fas fa-clock"></i> {{ \Carbon\CarbonImmutable::parse($msg->created_at)->locale( app()->getLocale() )->isoFormat('HH:mm:ss') }}</span>
                    <!-- Header. Optional -->
                    <h3 class="timeline-header"><a href="#">{{ App\Models\User::find($msg->notifiable_id)->name }}</a> {{ Str::title($msg->data['subject']) }}</h3>
                    <!-- Body -->
                    <div class="timeline-body">
                    {!! Str::limit( Str::of($msg->data['greeting'].', '.$msg->data['lines'])->ltrim(','), 40) !!}
                    </div>
                    <div class="timeline-footer">
                        <button class="btn btn-primary btn-sm" id="btnShowMessage" data-title="{{ $msg->data['subject'] }}" data-body="{{ $msg->data['lines']}}" data-greeting="{{ $msg->data['greeting'] ?? ''}}" data-salutation="{{ $msg->data['salutation'] ?? ''}}" >{{ __('message.read_more') }}</button>
                        <a class="btn btn-danger btn-sm" href="{{ route('message.mark_as_read', ['message'=>$msg->id])}}">{{ __('message.delete')}}</a>
                    </div>
                </div>
                </div>
        @endforeach
    @endforeach
    <!-- The last icon means the story is complete -->
    <div>
      <i class="fas fa-clock bg-gray"></i>
    </div>
  </div>
</div>
</div>
