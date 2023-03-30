<?php

namespace App\Exports;

use App\Traits\ImportErrorHandler;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CustomLeagueGameImportValidation implements FromCollection, WithStyles
{
    use ImportErrorHandler;

    private array $rows;
    private array $failures;

    public function __construct(array $rows, array $failures)
    {
        $this->rows = $rows;
        $this->failures = $failures;
    }

    public function collection()
    {
        return collect($this->rows);
    }
    public function styles(Worksheet $sheet)
    {
        // set error style
        foreach ($this->failures as $err) {
            // $sheet->getStyle('B2')->getFont()->setBold(true);
            [$erow, $ecol, $etxt] = $this->buildValidationMessage($err);
            $ecol = chr($ecol + 65);

            $sheet->getComment($ecol . $erow)->getText()->createTextRun($etxt . ' - ');
            $sheet->getStyle($ecol . $erow)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FFB266');
        }
    }
}
