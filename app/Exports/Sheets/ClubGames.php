<?php

namespace App\Exports\Sheets;

use App\Models\Game;
use App\Models\Club;
use App\Models\League;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ClubGames implements FromView, WithTitle, WithMapping, ShouldAutoSize, WithEvents
{

    protected $gdate = null;
    protected $club;
    protected $scope;

    protected $r_t_1 = 1;
    protected $r_h_1;
    protected $r_b_1_s;
    protected $r_b_1_e;

    public function __construct(Club $club, $scope)
    {
        $this->club = $club;
        $this->gdate = null;
        $this->scope = $scope;

        Log::info('sheet for '.$this->club->name);
    }

    /**
     * @return string
     */
    public function title(): string
    {
      if ( $this->scope == 'HOME') {
        return 'Heimspielplan ' . $this->club->shortname;
      } else {
        return 'Gesamtspielplan ' . $this->club->shortname;
      }
    }

    public function view(): View
    {
      if ($this->scope == 'HOME'){
         $games =  Game::where('club_id_home',$this->club->id)
                      ->with('league','gym')
                      ->orderBy('game_date','asc')
                      ->orderBy('game_time','asc')
                      ->orderBy('game_no','asc')
                      ->get();
      } else {
        $games =  Game::where('club_id_home',$this->club->id)
                     ->orWhere('club_id_guest',$this->club->id)
                     ->with('league','gym')
                     ->orderBy('game_date','asc')
                     ->orderBy('game_time','asc')
                     ->orderBy('game_no','asc')
                     ->get();
      }

      $extra_date_rows = $games->pluck('game_date')->unique()->count();
      Log::info($extra_date_rows);
      // set rows
      $this->r_h_1 = $this->r_t_1 + 2;
      $this->r_b_1_s = $this->r_h_1 + 1;
      $this->r_b_1_e = $this->r_h_1 + $games->count() + $extra_date_rows;

      return view('reports.game_club', ['games'=>$games, 'club'=>$this->club, 'gdate'=>$this->gdate]);
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
              $last_column = Coordinate::stringFromColumnIndex(8);

              // title setting (insert 2 rows before row 1)
              $event->sheet->insertNewRowBefore(1, 2);

              // Set first row to height 20
              $event->sheet->getDelegate()->getRowDimension($this->r_t_1)->setRowHeight(20);
              $event->sheet->getDelegate()->getRowDimension($this->r_t_1+1)->setRowHeight(20);

              // set title and date
              $event->sheet->setCellValue('A1',$this->club->name);
              $style_title = [
                  'alignment' => [
                      'horizontal' => Alignment::HORIZONTAL_LEFT
                  ],
                  'font' => [
                    'bold' => true,
                    'size' => 16,
                  ]
              ];
              $event->sheet->getStyle(sprintf('A%d:%s%d',$this->r_t_1, $last_column, $this->r_t_1))->applyFromArray($style_title);

              $style_title = [
                  'alignment' => [
                      'horizontal' => Alignment::HORIZONTAL_RIGHT
                  ],
                  'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'DC7633'],
                    'size' => 10,
                  ]
              ];
              $event->sheet->getStyle(sprintf('A%d:%s%d',$this->r_t_1+1, $last_column, $this->r_t_1+1))->applyFromArray($style_title);
              $event->sheet->setCellValue('A2','Stand: '.Carbon::now()->locale( app()->getLocale())->isoFormat('llll'));

              // merge cells for full-width
              $event->sheet->mergeCells(sprintf('A%d:%s%d',$this->r_t_1, $last_column, $this->r_t_1));
              $event->sheet->mergeCells(sprintf('A%d:%s%d',$this->r_t_1+1, $last_column, $this->r_t_1+1));

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
                    // 'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 14,
                  ],
                  'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => '0B6FA4'],
                  ]
              ];

              // assign cell styles
              $event->sheet->getStyle(sprintf('A%d:%s%d',$this->r_h_1, $last_column, $this->r_h_1))->applyFromArray($style_heading);

              $cellRange = sprintf('A%d:%s%d',$this->r_b_1_s, $last_column, $this->r_b_1_e);
              $style_body = [
                  'font' => [
                    'size' => 12,
                  ]
                ];
              $event->sheet->getStyle($cellRange)->applyFromArray($style_body);

              $cellRange = sprintf('A%d:%s%d',$this->r_t_1, $last_column, $this->r_b_1_e);
              $style_box = [
                  'borders' => [
                      'outline' => [
                          'borderStyle' => Border::BORDER_THIN,
                          'color' => ['rgb' => '000000'],
                      ]
                  ]
                ];
               $event->sheet->getStyle($cellRange)->applyFromArray($style_box);

                // // at row 1, insert 2 rows
                // $event->sheet->insertNewRowBefore(1, 2);
                //
                //
                // // assign cell values
                // $event->sheet->setCellValue('A1','Top Triggers Report');
                // $event->sheet->setCellValue('A2','SECURITY CLASSIFICATION - UNCLASSIFIED [Generator: Admin]');
                // $event->sheet->setCellValue(sprintf('A%d',$this->r_b_1_e + 1),'SECURITY CLASSIFICATION - UNCLASSIFIED [Generated: ...]');
                //
                //
                // // Apply array of styles to B2:G8 cell range
                // $styleArray = [
                //     'borders' => [
                //         'outline' => [
                //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                //             'color' => ['argb' => 'FFFF0000'],
                //         ]
                //     ]
                // ];
                // $event->sheet->getDelegate()->getStyle('B2:G8')->applyFromArray($styleArray);
                //
            },
        ];
    }

}
