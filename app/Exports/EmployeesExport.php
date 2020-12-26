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
						$method = str_replace('_id', '', $col);
						$method = \Str::camel($method);
						$obj[] = $entry->personalData->{$method}->name;
						continue;
					}
				}
    			$obj[] = null;
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
