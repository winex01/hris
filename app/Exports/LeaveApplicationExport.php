<?php

namespace App\Exports;

use App\Exports\BaseExport;

class LeaveApplicationExport extends BaseExport
{
    public function __construct($data)
    {
        parent::__construct($data);

        $this->setWrapText = true;

        $this->exportColumns = collect($this->exportColumns)->mapWithKeys(function ($item, $key) {
            if ($key == 'leave_approver_id') {
                $key .= '_custom_map';
            }

            return [$key => $item];
        })->toArray();

        // debug($data);
    }

    protected function orderByCurrentColumnOrder($col, $direction)
    {
        if ($col == 'status') {
            
            $this->query->orderByStatus($direction);
        
        }else {

            $this->orderBy($col, $direction);

        }
    }

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
        if ($filter == 'select2_multiple_approvers') {
            $this->query->whereHas('leaveApprover', function ($query) use ($values) {
                $query->approversEmployeeId($values);
            });
        } 
    }

    protected function customMap($col, $entry, $dataType)
    {
        if ($col == 'leave_approver_id_custom_map') {
            $temp = $entry->leaveApprover->approvers;
            return jsonToArrayImplode($temp, 'employee_name', ", \n");
        }
    }
}
