<?php

namespace App\Exports;

use App\Models\AwardAndRecognition;
use Maatwebsite\Excel\Concerns\FromCollection;

class GeneralExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AwardAndRecognition::all();
    }
}
