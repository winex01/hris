<?php

namespace App\Exports;

use App\Exports\BaseExport;

class ShiftScheduleExport extends BaseExport
{
    public static function exportColumnCheckboxes()
    {
    	$columns = getTableColumns('shift_schedules');

    	return collect($columns)->map(function ($item) {
			if ($item == 'working_hours') {
		  		$item = 'accessor_working_hours_as_export';
		  	}else if ($item == 'overtime_hours') {
		  		$item = 'accessor_overtime_hours_as_export';
		  	}
		  	
		  	return $item;
    	})->toArray();
    }

    public function dbColumnsWithDataType()
    {
      	$columns = getTableColumnsWithDataType('shift_schedules');

		$columns = collect($columns)->keyBy(function ($item, $key) {
			return ($item == 'json') ? 'accessor_'.$key.'_as_export' : $key;
		})->map(function ($item, $key) {
			return ($item == 'json') ? 'text' : $item;
		})->toArray();

		return $columns;
    }
}
