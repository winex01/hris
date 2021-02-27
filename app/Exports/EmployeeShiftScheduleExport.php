<?php

namespace App\Exports;

use App\Exports\BaseExport;
use Illuminate\Support\Str;

class EmployeeShiftScheduleExport extends BaseExport
{
	protected function orderBy($column, $orderBy)
    {   
    	$daysOfWeek = classInstance('\App\Http\Controllers\Admin\EmployeeShiftScheduleCrudController', true)->daysOfWeek();
    	$daysOfWeek = collect($daysOfWeek)->map(function ($item, $key) {
			$item = str_replace('_id', '', $item);	
		  	return $item;
		})->toArray();

        if ($column == 'employee') {
            $this->orderByEmployee($orderBy);
        }elseif (method_exists($this->model, Str::camel($column))) {
            $joinTable = Str::plural($column);

            if (in_array($column, $daysOfWeek)) {
            	$joinTable = 'shift_schedules';
            }

            $this->query->join($joinTable, $joinTable.'.id', '=', $this->currentTable.'.'.$column.'_id')
                ->orderBy($joinTable.'.name', $orderBy);  
        }else {
            $this->query->orderBy($column, $orderBy);
        }
    }
}
