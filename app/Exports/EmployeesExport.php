<?php

namespace App\Exports;

use App\Exports\GeneralExport;

class EmployeesExport extends GeneralExport
{
    
    public function query()
    {
    	if ($this->entries) {
            $ids_ordered = implode(',', $this->entries);

    		return $this->model::query()
                ->whereIn('id', $this->entries)
                ->orderByRaw("FIELD(id, $ids_ordered)");
    	}
        
        $column_direction = 'ASC';
        return $this->model::query()
            ->orderBy('last_name', $column_direction)
            ->orderBy('first_name', $column_direction)
            ->orderBy('middle_name', $column_direction)
            ->orderBy('badge_id', $column_direction);
    }

    public function map($entry): array
    {
        $obj = parent::map($entry);

		foreach (self::personalDataColumns() as $col) {
			if (in_array($col, $this->userFilteredColumns)) {
				if ($entry->personalData) {
					if (stringContains($col, '_id')) {
						$method = relationshipMethodName($col);
						$obj[] = $entry->personalData->{$method}->name;
					}else {
                        $obj[] = $entry->personalData->{$col};
                    }
                    continue;
				}
    			$obj[] = null;
			}//end if in_array
		}//end foreach  

        // TODO:: emergency contact      
        // TODO:: fathers info      
        // TODO:: mothers info      
        // TODO:: spouse info      

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
    	$data = array_merge(getTableColumns('employees'), self::personalDataColumns());
        // $data = array_merge($data, [
        //     'emergency_contact',
        //     'fathers_info',
        //     'mothers_info',
        //     'spouse_info',
        // ]);

    	return $data;
    }

    public static function checkOnlyCheckbox()
    {
        return getTableColumns('employees');
    }

    private static function personalDataColumns()
    {
    	return getTableColumns('personal_datas', [
    		'employee_id'
    	]);
    }


}
