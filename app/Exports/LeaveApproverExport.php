<?php

namespace App\Exports;

use App\Exports\BaseExport;
use Illuminate\Support\Str;

class LeaveApproverExport extends BaseExport
{
    protected function orderColumnAsEmployeesTable()
    {
        $result = parent::orderColumnAsEmployeesTable();
        $result[] = 'approver';

        return $result;
    }    
}
