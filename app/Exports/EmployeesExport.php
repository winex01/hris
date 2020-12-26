<?php

namespace App\Exports;

use App\Exports\GeneralExport;
use App\Models\Employee;

class EmployeesExport extends GeneralExport
{
    
    public function query()
    {
        return Employee::query();
    }

    public function map($entry): array
    {
        $obj = parent::map($entry);

		foreach (self::personalDataColumns() as $col) {
			if (in_array($col, $this->userFilteredColumns)) {
        		if ($entry->personalData) {
        			$obj[] = $entry->personalData->{$col};
        		}else {
        			$obj[] = null;
        		}//end if else
			}//end if in_array
		}//end foreach        

        return $obj;
    }

    public function headings(): array
    {
        $header = parent::headings();

		foreach (self::personalDataColumns() as $col) {
			if (in_array($col, $this->userFilteredColumns)) {
				$col = str_replace('_id', '', $col);
	            $col = str_replace('_', ' ', $col);
	            $col = ucwords($col);
	            
				$header[] = $col;
			}
		}

        return $header;
    }

    // export columns filter checkbox beside export button
    public static function exportColumnCheckboxes()
    {
    	$data = [
    		'badge_id',
    		'last_name',
    		'first_name',
    		'middle_name',
			// 'emergency_contact',
			// 'fathers_info',
			// 'mothers_info',
			// 'spouse_info',
    	];

    	$data = array_merge($data, self::personalDataColumns());

    	return $data;
    }

    private static function personalDataColumns()
    {
    	return getTableColumns('personal_datas', [
    		'employee_id'
    	]);
    }


}
