<?php

namespace App\Imports;

use App\Exports\CustomLeagueGameImportValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Facades\Excel;

class ImportValidationResults implements ToCollection
{

    private $importFile;
    private $failures;

    public function __construct($importFile, array $failures)
    {
        $this->importFile = $importFile;
        $this->failures = $failures;
    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {

        Excel::store(
            new CustomLeagueGameImportValidation($rows->toArray(), $this->failures),
            'Validated_' . $this->importFile->getClientOriginalName(),
            null,
            \Maatwebsite\Excel\Excel::XLSX
        );
    }
}
