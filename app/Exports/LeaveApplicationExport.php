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

    // override this if you want to modify what column shows in column dropdown with checkbox
    public static function exportColumnCheckboxes()
    {   
        $temp = getTableColumns('leave_applications');
        
        return removeFromArrays($temp, 'approved_approvers'); // remove this column
    }

    protected function select2MultipleFilters($filter, $values)
    {
        if ($filter == 'approvers') {
            $this->query->whereHas('leaveApprover', function ($query) use ($values) {
                $query->approversEmployeeId($values);
            });
        } 
    }
}
