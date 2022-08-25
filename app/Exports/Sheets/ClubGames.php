<?php

namespace App\Exports\Sheets;

use App\Models\Game;
use App\Models\Club;
use App\Enums\ReportScope;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;

class ClubGames implements FromView, WithTitle, ShouldAutoSize
{

    protected ?Date $gdate = null;
    protected Club $club;
    protected ReportScope $scope;

    public function __construct(Club $club, ReportScope $scope)
    {
        $this->club = $club;
        $this->gdate = null;
        $this->scope = $scope;

        Log::info('[EXCEL EXPORT] creating CLUB GAMES sheet.', ['club-id'=>$this->club->id]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
      if ( $this->scope == ReportScope::ss_club_home()) {
        $title =  __('reports.games.home').' ' . $this->club->shortname;
      } elseif ( $this->scope == ReportScope::ss_club_all()) {
        $title =  __('reports.games.all').' ' . $this->club->shortname;
      } elseif ( $this->scope == ReportScope::ss_club_referee()) {
        $title =  __('reports.games.referee').' ' . $this->club->shortname;
      }
      return $title ?? '';
    }

    public function view(): View
    {
      if ($this->scope == ReportScope::ss_club_home()){
         $games =  Game::where('club_id_home',$this->club->id)
                      ->with('league','gym')
                      ->orderBy('game_date','asc')
                      ->orderBy('game_time','asc')
                      ->orderBy('game_no','asc')
                      ->get();
      } elseif ($this->scope == ReportScope::ss_club_all()) {
        $games =  Game::where('club_id_home',$this->club->id)
                     ->orWhere('club_id_guest',$this->club->id)
                     ->with('league','gym')
                     ->orderBy('game_date','asc')
                     ->orderBy('game_time','asc')
                     ->orderBy('game_no','asc')
                     ->get();
      } elseif ($this->scope == ReportScope::ss_club_referee()) {
        $club_id = $this->club->id;
        $shortname = $this->club->shortname;
        $games = Game::where( function ($query) use ($club_id) {
                       $query->where('club_id_home',$club_id)
                             ->where('referee_1','****');
                     })
                     ->orWhere( function ($query) use ($shortname) {
                       $query->where('referee_1',$shortname)
                             ->orWhere('referee_2',$shortname);
                     })
                     ->with('league','gym')
                     ->orderBy('game_date','asc')
                     ->orderBy('game_time','asc')
                     ->orderBy('game_no','asc')
                     ->get();

      } else {
          $games = collect();
      }


      return view('reports.games_sheet', ['games'=>$games, 'gdate'=>$this->gdate,'gtime'=>null, 'with_league'=>true]);
    }

/*     public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {
              $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
              $event->sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

            },
        ];
    } */

}
