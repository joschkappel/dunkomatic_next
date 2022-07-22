<?php

namespace App\Exports\Sheets;

use App\Models\Game;
use App\Models\League;
use App\Models\Region;
use App\Models\Team;
use App\Models\Club;
use App\Models\Gym;
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
use PhpOffice\PhpSpreadsheet\Shared\Date;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class RegionGames implements FromView, WithTitle, ShouldAutoSize, WithEvents
{

    protected ?Date $gdate = null;
    protected League $league;

    protected int $r_t_1 = 1;
    protected int $r_h_1;
    protected int $r_b_1_s;
    protected int $r_b_1_e;
    protected int $r_t_2;
    protected int $r_h_2;
    protected int $r_b_2_s;
    protected int $r_b_2_e;

    public function __construct(Region $region)
    {
        $this->gdate = null;
        $this->region = $region;

        Log::info('[EXCEL EXPORT] creating REGION GAMES sheet.', ['region-id'=>$this->region->id]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
       return __('reports.games.all').' '.$this->region->code;
    }

    public function view(): View
    {
        $games =  Game::where('region',$this->region->code)
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

        $extra_date_rows = $games->pluck('game_date')->unique()->count();
        // set rows
        $this->r_h_1 = $this->r_t_1 + 2;
        $this->r_b_1_s = $this->r_h_1 + 1;
        $this->r_b_1_e = $this->r_h_1 + $games->count() + $extra_date_rows;

        $this->r_t_2 = $this->r_b_1_e + 1;
        $this->r_h_2 = $this->r_t_2 + 3;
        $this->r_b_2_s = $this->r_h_2;
        $this->r_b_2_e = $this->r_h_2 + ( $clubs->count() + (2*$g) + (2*$t) );

/*         Log::info($this->r_b_1_e);
        Log::info($this->r_t_2);
        Log::info($this->r_h_2);
        Log::info($this->r_b_2_s);
        Log::info($this->r_b_2_e); */

        return view('reports.game_region', ['games'=>$games,'clubs'=>$clubs,'region'=>$this->region, 'gdate'=>$this->gdate]);
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
                $event->sheet->insertNewRowBefore($this->r_b_1_e+1, 2);

                // Set first row to height 20
                $event->sheet->getDelegate()->getRowDimension($this->r_t_1)->setRowHeight(20);
                $event->sheet->getDelegate()->getRowDimension($this->r_t_1+1)->setRowHeight(20);
                $event->sheet->getDelegate()->getRowDimension($this->r_t_2+2)->setRowHeight(20);

                // set title and date
                $event->sheet->setCellValue('A1',$this->region->name);
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
                $event->sheet->getStyle(sprintf('A%d:%s%d',$this->r_t_2+2, $last_column, $this->r_t_2+2))->applyFromArray($style_title);

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
                $event->sheet->setCellValue('A'.($this->r_t_2+2), __('game.team.gym'));

                // merge cells for full-width
                $event->sheet->mergeCells(sprintf('A%d:%s%d',$this->r_t_1, $last_column, $this->r_t_1));
                $event->sheet->mergeCells(sprintf('A%d:%s%d',$this->r_t_1+1, $last_column, $this->r_t_1+1));
                $event->sheet->mergeCells(sprintf('A%d:%s%d',$this->r_t_2, $last_column, $this->r_t_2+1));
                $event->sheet->mergeCells(sprintf('A%d:%s%d',$this->r_t_2+2, $last_column, $this->r_t_2+2));

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
                $style_title = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT
                    ],
                    'font' => [
                      'bold' => true,
                      'size' => 16,
                    ]
                ];

                $cellRange = sprintf('A%d:%s%d',$this->r_b_1_s, $last_column, $this->r_b_1_e);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);

                $style_body2 = [
                    'alignment' => [
                        'wrapText' => true,
                    ],
                    'font' => [
                      'size' => 12,
                    ]
                ];
                $event->sheet->getStyle(sprintf('A%d:%s%d',$this->r_b_2_s, $last_column, $this->r_b_2_e))->applyFromArray($style_body2);


                //merge cells for teams and gym details
                for ($i = $this->r_b_2_s; $i <= $this->r_b_2_e; $i++){
                  $event->sheet->mergeCells(sprintf('B%d:E%d',$i, $i));
                  $event->sheet->mergeCells(sprintf('F%d:G%d',$i, $i));
                }

                $style_box = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ]
                    ]
                  ];
                 $cellRange = sprintf('A%d:%s%d',$this->r_t_1, $last_column, $this->r_b_1_e);
                 $event->sheet->getStyle($cellRange)->applyFromArray($style_box);
                 $cellRange = sprintf('A%d:%s%d',$this->r_t_2, $last_column, $this->r_b_2_e);
                 $event->sheet->getStyle($cellRange)->applyFromArray($style_box);

              }
        ];
    }

}
