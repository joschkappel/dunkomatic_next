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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;

class ClubGames implements FromView, WithTitle, ShouldAutoSize, WithEvents
{

    protected ?Date $gdate = null;
    protected Club $club;
    protected ReportScope $scope;

    protected int $r_t_1 = 1;
    protected int $r_h_1;
    protected int $r_b_1_s;
    protected int $r_b_1_e;

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
                     ->orderBy('game_date','asc')
                     ->orderBy('game_time','asc')
                     ->orderBy('game_no','asc')
                     ->get();

      } else {
          $games = collect();
      }

      $extra_date_rows = $games->pluck('game_date')->unique()->count();
      //Log::info($extra_date_rows);
      // set rows
      $this->r_h_1 = $this->r_t_1 + 2;
      $this->r_b_1_s = $this->r_h_1 + 1;
      $this->r_b_1_e = $this->r_h_1 + $games->count() + $extra_date_rows;

      return view('reports.game_club', ['games'=>$games, 'club'=>$this->club, 'gdate'=>$this->gdate]);
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

            },
        ];
    }

}
