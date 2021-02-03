<?php

namespace App\Exports;

use App\Exports\GeneralExport;

class EmployeeExport extends GeneralExport
{
    protected function orderBy($column, $orderBy)
    {   
        switch ($column) {
            case 'employee':
                $this->orderByEmployee($orderBy);
                break;

			case 'badge':
			case 'gender':
			case 'civil_status':
			case 'citizenship':
			case 'religion':
			case 'blood_type':
				// TODO:; create local scope for relationship to order this correctly
				$column .= '_id';
                $this->query->orderBy($column, $orderBy);
                break;                

            default:
                $this->query->orderBy($column, $orderBy);
                break;
        }// end switch
    }
}
