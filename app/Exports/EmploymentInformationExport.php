<?php

namespace App\Exports;

use App\Exports\BaseExport;
use Illuminate\Support\Str;

class EmploymentInformationExport extends BaseExport
{
    protected function orderBy($column, $orderBy)
    {
    	$column = ($column == 'date_change') ? 'created_at' : $column;

    	$originalFunc = parent::orderBy($column, $orderBy);

    	$this->query->orderByField();

    	return $originalFunc;
    }

    // custom checklists of Column Export dropdown
    public static function exportColumnCheckboxes()
    {
        return [
            'employee_id', 
            'field_name', 
            'field_value', 
            'effectivity_date', 
            'created_at',
        ];
    }

    public function dbColumnsWithDataType()
    {
        $originalFunc = parent::dbColumnsWithDataType();
        $originalFunc['created_at'] = 'timestamp';

   		return $originalFunc;
    }    
}
