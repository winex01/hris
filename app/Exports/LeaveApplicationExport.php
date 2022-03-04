<?php

namespace App\Exports;

use App\Exports\BaseExport;

class LeaveApplicationExport extends BaseExport
{
    protected function changeColumnValue($col, $value)
    {
    	$status = strtolower($value);

        if ($col == 'status') {
        	if ($status == 0) {
        		$status = 'Pending';
        	}elseif ($status == 1) {
        		$status = 'Approved';
        	}elseif ($status == 2) {
                $status = 'Denied';
            }else {
        		//
        	}
            return ucwords($status);
        }

        return $value;
    }
}
