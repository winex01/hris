<?php

namespace App\Exports;

use App\Exports\BaseExport;

class PayrollPeriodExport extends BaseExport
{
    protected function changeBooleanLabels($col, $value)
    {
    	$value = strtolower($value);

        if ($col == 'status') {
        	if ($value == 'no') {
        		$value = 'Close';
        	}elseif ($value == 'yes') {
        		$value = 'Open';
        	}else {
        		//
        	}
        }

        return ucwords($value);
    }
}
