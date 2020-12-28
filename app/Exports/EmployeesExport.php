<?php

namespace App\Exports;

use App\Exports\GeneralExport;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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

		foreach (self::personalDataColumns() as $col => $dataType) {
			if (in_array($col, $this->userFilteredColumns)) {
				if ($entry->personalData) {
					if (stringContains($col, '_id')) {
						$method = relationshipMethodName($col);
                        if ($entry->personalData->{$method}) {
                            $obj[] = $entry->personalData->{$method}->name;
                        }else {
                            $obj[] = null;
                        }
					}else {
                        $value = $entry->personalData->{$col};
                        if ($dataType == 'date') {
                            $value = Date::PHPToExcel($value); 
                        }
                        $obj[] = $value;
                    }
                    continue;
				}
                $obj[] = null;
			}//end if in_array
		}//end foreach  

        // related persons: contact, fathers, mothers, spouse info
        foreach ($this->relatedPerson() as $person) {
            if (in_array($person, $this->userFilteredColumns)) {
                $method = str_replace('_info', '', $person);
                $method = \Str::singular($method);
                $method = relationshipMethodName($method);
                foreach ($this->personDataColumns() as $col) {
                    if ($entry->{$method}()) {
                        $obj[] = $entry->{$method}()->{$col};
                    }else {
                        $obj[] = null;
                    }
                }
            }
        }  

        return $obj; 
    }

    public function headings(): array
    {
        $header = parent::headings();

        // personal data
		foreach (self::personalDataColumns() as $col => $dataType) {
			if (in_array($col, $this->userFilteredColumns)) {
				$header[] = convertColumnToHumanReadable($col);
			}
		}

        // related persons: contact, fathers, mothers, spouse info
        foreach ($this->relatedPerson() as $person) {
            if (in_array($person, $this->userFilteredColumns)) {
                foreach ($this->personDataColumns() as $col => $dataType) {
                    $prefix = trans('lang.employee_export_'.$person);
                    $header[] = $prefix.' '.convertColumnToHumanReadable($col);
                }
            }
        }

        return $header;
    }

    public function columnFormats(): array
    {
        $columnFormats = parent::columnFormats();
        
        if ($columnFormats) {
            $inc = collect($columnFormats)->keys()->last();
            $inc++;
        }else {
            $inc = 'A';
        }
        
        // personal data
        foreach (self::personalDataColumns() as $col => $dataType) {
            if (in_array($col, $this->userFilteredColumns)) {
                if (array_key_exists($dataType, $this->formats)) {
                    $columnFormats[$inc] = $this->formats[$dataType];
                }
                $inc++;
            }//end in_array
        }

        // related persons 
        foreach ($this->relatedPerson() as $person) {
            if (in_array($person, $this->userFilteredColumns)) {
                foreach ($this->personDataColumns() as $col => $dataType) {
                    if (array_key_exists($dataType, $this->formats)) {
                        $columnFormats[$inc] = $this->formats[$dataType];
                    }
                    $inc++;
                }
            }
        }

        // debug($columnFormats);
        return $columnFormats;
    }

    public static function exportColumnCheckboxes()
    {
        $data = array_merge(
            getTableColumns('employees'), 
            getTableColumns('personal_datas', [
                'employee_id'
            ]), 
        );

        $data = array_merge($data, self::relatedPerson());

        return $data;
    }

    public static function checkOnlyCheckbox()
    {
        return getTableColumns('employees');
    }

    private static function relatedPerson()
    {
        return [
            'emergency_contact',
            'fathers_info',
            'mothers_info',
            'spouse_info',
        ];
    }

    private static function personalDataColumns()
    {
    	return getTableColumnsWithDataType('personal_datas', [
    		'employee_id'
    	]);
    }

    private function personDataColumns()
    {
        return getTableColumnsWithDataType('persons', [
            'relation'
        ]);
    }


}
