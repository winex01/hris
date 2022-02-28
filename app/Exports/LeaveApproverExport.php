<?php

namespace App\Exports;

use App\Exports\BaseExport;

class LeaveApproverExport extends BaseExport
{
    protected function changeColumnValue($col, $value)
    {
    	$value = strtolower($value);

        if ($col == 'approvers') {
        	return 'hehe';
        }

        return $value;
    }
}
