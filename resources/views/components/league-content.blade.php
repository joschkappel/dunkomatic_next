{{-- <div class="progress" style="height: 20px;">
   @foreach ( $league_content as $team )
        @if ( $team['team_league_no'] != null )
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $item_space }}%">{{ $team['club_shortname'] }}</div>
        @else
            @if ($team['team_id'] != null)
                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $item_space }}%">{{ $team['club_shortname'] }}</div>
            @else
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $item_space }}%">{{ $team['club_shortname'] }}</div>
            @endif
        @endif
   @endforeach
</div> --}}
<div>
    @foreach ( $league_content as $team )
         @if ( $team['team_league_no'] != null )
             <span class="badge text-xs badge-success">{{ $team['club_shortname'].$team['team_no'].' ('.$team['team_league_no'].')'. }}</span>
         @else
             @if ($team['team_id'] != null)
             <span class="badge text-xs badge-warning">{{ $team['club_shortname'].$team['team_no'] }}</span>
             @else
             <span class="badge text-xs badge-primary">{{ $team['club_shortname'] }}</span>
             @endif
         @endif
    @endforeach
 </div>


