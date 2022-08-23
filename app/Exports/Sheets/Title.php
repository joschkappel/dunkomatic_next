<?php

namespace App\Exports\Sheets;

use App\Models\Region;
use App\Models\Club;
use App\Models\League;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class Title implements FromView, WithTitle, ShouldAutoSize, WithEvents
{
    protected string $organization;
    protected string $rpt_subtitle;
    protected string $rptname;

    public function __construct(string $rptname, Region $region=null, Club $club=null, League $league=null )
    {

        if ($region != null){
            $this->organization = $region->is_top_level ? $region->name : $region->parentRegion->name;
            $this->rpt_subtitle = $region->name;
            $this->rptname = $rptname.' '.$region->code;
        } else {
            if ($club != null){
                $this->organization = $club->region->parentRegion->name;
                $this->rpt_subtitle = $club->name;
                $this->rptname = $rptname.' '.$club->shortname;
            } else {
                if ($league != null){
                    $this->organization = $league->region->is_top_level ? $league->region->name : $league->region->parentRegion->name;
                    $this->rpt_subtitle = $league->name;
                    $this->rptname = $rptname.' '.$league->shortname;
                } else {
                    $this->organization = 'unknonw';
                    $this->rpt_subtitle = '';
                    $this->rptname = $rptname;
                }
            }
        }
    }

    public function view(): View
    {
        return view('reports.title_sheet', ['organization' => $this->organization, 'rpt_title' => $this->rptname, 'rpt_subtitle'=>$this->rpt_subtitle]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
       return $this->rptname;
    }
    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {
              $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
              $event->sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            }
        ];
    }

}
