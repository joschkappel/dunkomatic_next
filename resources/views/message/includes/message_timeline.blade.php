<div class="card card-outline card-success">
    <div class="card-header">
        <h4 class="card-title font-weight-bold pt-2"><i class="fas fa-envelope text-success mx-2"></i>{{ trans_choice('message.message',2) }}</h4>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
<!-- Main node for this component -->
  <div class="timeline">
    @empty ($msglist)
    <div class="time-label">
        <span class="bg-info">{{__('message.message.empty')}}</span>
    </div>
    @endempty
    @foreach ( $msglist as $msgdate)
    <!-- Timeline time label -->
    <div class="time-label">
      <span class="bg-green text-xs">{{ \Carbon\CarbonImmutable::parse($msgdate['valid_from'])->locale( app()->getLocale() )->isoFormat('ll') }}</span>
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
                    <div class="timeline-header text-sm">{{ isset($msg->data['sender']) ?  $msg->data['sender'].': ' : '' }}<a href="#" class="text-sm" onClick="btnShowMessage('{{$msg->id}}','{{$msg->data['subject']}}','{{$msg->data['greeting']}}','{{$msg->data['lines']}}','{{$msg->data['salutation']}}')">{!! Str::title($msg->data['subject']) !!}</a></div>

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
