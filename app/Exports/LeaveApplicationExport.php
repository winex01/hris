<?php

namespace App\Exports;

use App\Exports\BaseExport;

class LeaveApplicationExport extends BaseExport
{
    protected function changeColumnValue($col, $value)
    {
    	$value = strtolower($value);

        if ($col == 'status') {
        	if ($value == 0) {
        		$value = 'Pending';
        	}elseif ($value == 1) {
        		$value = 'Approved';
        	}elseif ($value == 2) {
                $value = 'Denied';
            }else {
        		//
        	}
        }

        return ucwords($value);
    }
}
