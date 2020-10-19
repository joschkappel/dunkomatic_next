  <!-- Main node for this component -->
  <div class="timeline">

    @foreach ( $msglist as $msgdate)
    <!-- Timeline time label -->
    <div class="time-label">
      <span class="bg-green">{{ \Carbon\CarbonImmutable::parse($msgdate['valid_from'])->locale( app()->getLocale() )->isoFormat('ll') }}</span>
    </div>
      @foreach ( $msgdate['items'] as $msg )
    <div>
    <!-- Before each timeline item corresponds to one icon on the left scale -->
      <i class="fas fa-envelope bg-info "></i>
      <!-- Timeline item -->
      <div class="timeline-item">
      <!-- Time -->
        <span class="time"><i class="fas fa-clock"></i> {{ \Carbon\CarbonImmutable::parse($msg['created_at'])->locale( app()->getLocale() )->isoFormat('HH:mm:ss') }}</span>
        <!-- Header. Optional -->
        <h3 class="timeline-header"><strong>{{ $msg['author'] }}</strong>: {{ $msg['subject'] }}</h3>
        <!-- Body -->
        <div class="timeline-body">
          {!! $msg['body'] !!}
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
  Extra style
