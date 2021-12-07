  <!-- Main node for this component -->
  <div class="timeline">
    <div class="time-label">
        <span class="bg-danger">Reminders</span>
    </div>
    <div>
        @if ($reminders)
            <!-- Before each timeline item corresponds to one icon on the left scale -->
            <i class="fas fa-bell bg-danger "></i>
            <!-- Timeline item -->
            @foreach ($reminders as $r)
                <div class="timeline-item">
                    <div class="timeline-body">{{ $r['msg'] }}</div>
                    <div class="timeline-footer">
                        @if ($r['action'] != '')
                        <a class="btn btn-primary btn-sm" href="{{ $r['action'] }}">{{$r['actiontext']}}</a>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div>
        <!-- Before each timeline item corresponds to one icon on the left scale -->
        <i class="fas fa-info bg-info "></i>
        <!-- Timeline item -->
        @foreach ($infos as $i)
        <div class="timeline-item">
            <div class="timeline-body">{{ $i['msg'] }}</div>
            <div class="timeline-footer">
                @if ($i['action'] != '')
                <a class="btn btn-primary btn-sm" href="{{ $i['action'] }}">{{ $i['actiontext']}}</a>
                @endif
            </div>
        </div>
        @endforeach
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
                        @if ($msg->type != 'App\Notifications\AppActionMessage')
                        <button class="btn btn-primary btn-sm" id="btnShowMessage" data-title="{{ $msg->data['subject'] }}" data-body="{{ $msg->data['lines']}}" data-greeting="{{ $msg->data['greeting'] ?? ''}}" data-salutation="{{ $msg->data['salutation'] ?? ''}}" >{{ __('message.read_more') }}</button>
                        @endif
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
