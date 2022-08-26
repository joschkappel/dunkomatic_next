<?php

namespace App\Exports\Sheets;

use App\Models\Game;
use App\Models\League;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use Illuminate\Support\Facades\Log;

class LeagueGamesSheet implements FromView, WithTitle, ShouldAutoSize
{

    protected ?Date $gdate = null;
    protected League $league;


    public function __construct(League $league)
    {
        $this->gdate = null;
        $this->league = $league;

        Log::info('[EXCEL EXPORT] creating LEAGUE GAMES sheet.', ['league-id'=>$this->league->id]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
       return __('reports.games.league').' '.$this->league->shortname;
    }

    public function view(): View
    {
        $games =  Game::where('league_id',$this->league->id)
                      ->with('league')
                      ->orderBy('game_date','asc')
                      ->orderBy('game_time','asc')
                      ->orderBy('game_no','asc')
                      ->get();
        $league = $this->league->load('members');


        return view('reports.games_sheet', ['games'=>$games, 'gdate'=>$this->gdate, 'gtime'=>null, 'with_league'=>false, 'league'=>$league]);
    }


/*     public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $event->sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
              }
        ];
    } */

}
