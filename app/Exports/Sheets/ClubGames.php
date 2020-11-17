<?php

namespace App\Exports\Sheets;

use App\Models\Game;
use App\Models\Club;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Events\AfterSheet;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ClubGames implements FromQuery, WithTitle, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{

    protected $gdate = null;
    protected $club;
    protected $scope;

    public function __construct(Club $club, $scope)
    {
        $this->club = $club;
        $this->gdate = null;
        $this->scope = $scope;

        Log::info('sheet for '.$this->club->name);
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
      if ($this->scope == 'HOME'){
         return  Game::where('club_id_home',$this->club->id)
                      ->with('league','gym')
                      ->orderBy('game_date','asc')
                      ->orderBy('game_time','asc')
                      ->orderBy('game_no','asc');
      } else {
        return  Game::where('club_id_home',$this->club->id)
                     ->orWhere('club_id_guest',$this->club->id)
                     ->with('league','gym')
                     ->orderBy('game_date','asc')
                     ->orderBy('game_time','asc')
                     ->orderBy('game_no','asc');
      }
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
            $game->referee_1
        ];
    }
    public function headings(): array
    {
        return [
          [
            'Vereinsspielplan',
            'RUnde X',
            'Runde Y'
          ],
          [],
          [
            __('game.game_date'),
            __('game.game_time'),
            __('game.league'),
            __('game.game_no'),
            __('game.team_home'),
            __('game.team_guest'),
            __('game.gym_no'),
            __('game.referee')
          ]
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            3    => ['font' => ['bold' => true]],

        ];
    }

    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {

                // last column as letter value (e.g., D)
                $last_column = Coordinate::stringFromColumnIndex(7);

                // calculate last row + 1 (total results + header rows + column headings row + new row)
                $last_row = 188 + 2 + 1 + 1;

                // set up a style array for cell formatting
                $style_text_center = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ];

                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1, 2);

                // merge cells for full-width
                $event->sheet->mergeCells(sprintf('A1:%s1',$last_column));
                $event->sheet->mergeCells(sprintf('A2:%s2',$last_column));
                $event->sheet->mergeCells(sprintf('A%d:%s%d',$last_row,$last_column,$last_row));

                // assign cell values
                $event->sheet->setCellValue('A1','Top Triggers Report');
                $event->sheet->setCellValue('A2','SECURITY CLASSIFICATION - UNCLASSIFIED [Generator: Admin]');
                $event->sheet->setCellValue(sprintf('A%d',$last_row),'SECURITY CLASSIFICATION - UNCLASSIFIED [Generated: ...]');

                // assign cell styles
                $event->sheet->getStyle('A1:A2')->applyFromArray($style_text_center);
                $event->sheet->getStyle(sprintf('A%d',$last_row))->applyFromArray($style_text_center);

                $cellRange = 'A1:W1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                // Apply array of styles to B2:G8 cell range
                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000'],
                        ]
                    ]
                ];
                $event->sheet->getDelegate()->getStyle('B2:G8')->applyFromArray($styleArray);

                // Set first row to height 20
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);

                // Set A1:D4 range to wrap text in cells
                $event->sheet->getDelegate()->getStyle('A1:D4')
                    ->getAlignment()->setWrapText(true);
            },
        ];
    }

}
