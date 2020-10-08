  <!-- Main node for this component -->
  <div class="timeline">

    @foreach ( $msglist as $msgdate)
    <!-- Timeline time label -->
    <div class="time-label">
      <span class="bg-green">{{ \Carbon\Carbon::parse($msgdate['valid_from'])->locale( app()->getLocale() )->isoFormat('LL') }}</span>
    </div>
      @foreach ( $msgdate['items'] as $msg )
    <div>
    <!-- Before each timeline item corresponds to one icon on the left scale -->
      <i class="fas fa-envelope @if ($msg['type'] == 2) bg-primary @elseif ($msg['type'] == 1) bg-info @endif"></i>
      <!-- Timeline item -->
      <div class="timeline-item">
        <!-- Header. Optional -->

        <h3 class="timeline-header">@if ($msg['type'] == 2) {{ __('message.tl_to')}} @elseif ($msg['type'] == 1) {{__('message.tl_cc')}} @endif <strong>{{ $msg['author'] }}</strong></h3>
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
