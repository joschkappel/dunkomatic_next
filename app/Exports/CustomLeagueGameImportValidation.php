<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CustomLeagueGameImportValidation implements FromCollection, WithStyles
{
    private array $rows;
    private array $errors;

    public function __construct(array $rows, array $errors)
    {
        $this->rows = $rows;
        $this->errors = $errors;
    }

    public function collection()
    {
        // add error to rows
        foreach ($this->errors as $err) {
            array_push($this->rows[$err->row() - 1], $err->errors()[0]);
        }
        return collect($this->rows);
    }
    public function styles(Worksheet $sheet)
    {
        // set error style
        foreach ($this->errors as $err) {
            // $sheet->getStyle('B2')->getFont()->setBold(true);
            $sheet->getComment('A' . $err->row())->getText()->createTextRun('Error: ' . $err->errors()[0]);
            $sheet->getStyle('B2')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('DD4B39');
        }
    }
}
