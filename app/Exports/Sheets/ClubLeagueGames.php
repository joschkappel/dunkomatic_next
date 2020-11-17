<?php

namespace App\Exports\Sheets;

use App\Models\Game;
use App\Models\Club;
use App\Models\League;
use App\Models\Team;
use App\Models\Gym;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Events\AfterSheet;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ClubLeagueGames implements FromView, WithTitle, WithMapping, ShouldAutoSize, WithEvents
{

    protected $gdate = null;
    protected $club;
    protected $league;

    protected $r_t_1 = 1;
    protected $r_h_1;
    protected $r_b_1_s;
    protected $r_b_1_e;
    protected $r_t_2;
    protected $r_h_2;
    protected $r_b_2_s;
    protected $r_b_2_e;

    public function __construct(Club $club, League $league)
    {
        $this->club = $club;
        $this->gdate = null;
        $this->league = $league;

        Log::info('sheet for '.$this->league->name);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Team ' . $this->league->shortname;
    }

    public function view(): View
    {
        $games =  Game::where('league_id',$this->league->id)
                      ->where( function($q) {
                        $q->where('club_id_home',$this->club->id)
                          ->orWhere('club_id_guest',$this->club->id);
                        })
                      ->with('league')
                      ->orderBy('game_date','asc')
                      ->orderBy('game_time','asc')
                      ->orderBy('game_no','asc')
                      ->get();

        $guests = $games->pluck('club_id_guest')->unique();

        $clubs = Club::whereIn('id', $guests)
                ->orderBy('shortname')
                ->get();
        $g = 0;
        $t = 0;

        foreach ($clubs as $c){
          $c['teams'] = Team::whereIn('id', $games->where('club_id_home',$c->id)->pluck('team_id_home')->unique())->orderBy('team_no')->get();
          $t += $c['teams']->count();
          $c['gyms'] = Gym::whereIn('id', $games->where('club_id_home',$c->id)->pluck('gym_id')->unique())->orderBy('gym_no')->get();
          $g += $c['gyms']->count();
        }
        //Log::info(print_r($guests));
        //Log::info(print_r($clubs));

        // set rows
        $this->r_h_1 = $this->r_t_1 + 1;
        $this->r_b_1_s = $this->r_h_1 + 1;
        $this->r_b_1_e = $this->r_h_1 + $games->count();

        $this->r_t_2 = $this->r_b_1_e + 2;
        $this->r_h_2 = $this->r_t_2 + 1;
        $this->r_b_2_s = $this->r_h_2 + 1;
        $this->r_b_2_e = $this->r_h_2 + ( $clubs->count() + (2*$g) + (2*$t) );

        return view('reports.game', ['games'=>$games,'clubs'=>$clubs,'league'=>$this->league]);
    }

    /**
    * @var Game $game
    */
    public function map($game): array
    {
        if ( $this->gdate != $game->game_date){
          $this->gdate = $game->game_date;
        } else {
          $game->game_date = null;
        };
        return [
            ($game->game_date == null) ? '' : $game->game_date->locale( app()->getLocale())->isoFormat('ddd L'),
            Carbon::parse($game->game_time)->isoFormat('LT'),
            $game->league->shortname,
            $game->game_no,
            $game->team_home,
            $game->team_guest,
            $game->gym->name,
            implode(' / ',[$game->referee_1, $game->referee_2])
        ];
    }

    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $event->sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                // last column as letter value (e.g., D)
                $last_column = Coordinate::stringFromColumnIndex(7);

                // merge cells for full-width
                $event->sheet->mergeCells(sprintf('A%d:%s%d',$this->r_t_1, $last_column, $this->r_t_1));
                $event->sheet->mergeCells(sprintf('A%d:%s%d',$this->r_t_2, $last_column, $this->r_t_2));


                // set up a style array for header formatting
                $style_heading = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'FFFFFF'],
                        ]
                    ],
                    'font' => [
                      'bold' => true,
                      'color' => ['rgb' => 'FFFFFF'],
                      'size' => 16,
                    ],
                    'fill' => [
                      'fillType' => Fill::FILL_SOLID,
                      'color' => ['rgb' => '0B6FA4'],
                    ]
                ];


                // assign cell styles
                $event->sheet->getStyle(sprintf('A%d:%s%d',$this->r_h_1, $last_column, $this->r_h_1))->applyFromArray($style_heading);

              }
        ];
    }

}
