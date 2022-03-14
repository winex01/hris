<?php

namespace App\Exports;

use App\Exports\BaseExport;

class DailyTimeRecordExport extends BaseExport 
{
    /**
     * add ons order
     */
    protected function orderByAddOns()
    {
        $this->query->orderBy($this->currentTable.'.date');
    }
}
