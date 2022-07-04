<?php

namespace Database\Seeders\fixes;

use App\Models\Club;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InactiveClubs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // HBV-DA
        Club::where('shortname','DISB')->update(['inactive'=>true]);
        Club::where('shortname','TGRS')->update(['inactive'=>true]);
        Club::where('shortname','GEHE')->update(['inactive'=>true]);
        Club::where('shortname','MTVU')->update(['inactive'=>true]);
        Club::where('shortname','LAMP')->update(['inactive'=>true]);
        Club::where('shortname','WAMI')->update(['inactive'=>true]);
        Club::where('shortname','TAUN')->update(['inactive'=>true]);
        Club::where('shortname','WABA')->update(['inactive'=>true]);
        Club::where('shortname','SCNA')->update(['inactive'=>true]);
        Club::where('shortname','HIRS')->update(['inactive'=>true]);
        // HBV-GI
        Club::where('shortname','FRAN')->update(['inactive'=>true]);
        Club::where('shortname','VFBG')->update(['inactive'=>true]);
        Club::where('shortname','LICH')->update(['inactive'=>true]);
        Club::where('shortname','BSVB')->update(['inactive'=>true]);
        Club::where('shortname','TUBI')->update(['inactive'=>true]);
        // HBV-KS
        Club::where('shortname','ESWG')->update(['inactive'=>true]);
        Club::where('shortname','GSET')->update(['inactive'=>true]);
        Club::where('shortname','MELS')->update(['inactive'=>true]);
        Club::where('shortname','KORB')->update(['inactive'=>true]);
        Club::where('shortname','NEUK')->update(['inactive'=>true]);
        Club::where('shortname','TREY')->update(['inactive'=>true]);
        Club::where('shortname','HOFG')->update(['inactive'=>true]);
        Club::where('shortname','TUNI')->update(['inactive'=>true]);
        Club::where('shortname','DYWI')->update(['inactive'=>true]);
        // HBV-F
        Club::where('shortname','GSUF')->update(['inactive'=>true]);
        Club::where('shortname','ESCH')->update(['inactive'=>true]);
        Club::where('shortname','USIN')->update(['inactive'=>true]);
        Club::where('shortname','VOCK')->update(['inactive'=>true]);
        Club::where('shortname','SWAL')->update(['inactive'=>true]);
        Club::where('shortname','VILB')->update(['inactive'=>true]);
        Club::where('shortname','NOWE')->update(['inactive'=>true]);
        Club::where('shortname','SGHA')->update(['inactive'=>true]);
        Club::where('shortname','NRAD')->update(['inactive'=>true]);
        Club::where('shortname','DIET')->update(['inactive'=>true]);
        Club::where('shortname','GRAV')->update(['inactive'=>true]);
        Club::where('shortname','ASSE')->update(['inactive'=>true]);
        Club::where('shortname','FSCK')->update(['inactive'=>true]);
        Club::where('shortname','OLYM')->update(['inactive'=>true]);
        Club::where('shortname','JUNG')->update(['inactive'=>true]);
        Club::where('shortname','ROST')->update(['inactive'=>true]);
        Club::where('shortname','STST')->update(['inactive'=>true]);
        Club::where('shortname','ARTE')->update(['inactive'=>true]);
        Club::where('shortname','SPRO')->update(['inactive'=>true]);

    }
}
