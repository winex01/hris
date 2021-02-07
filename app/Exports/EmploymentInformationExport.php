<?php

namespace App\Exports;

use App\Exports\BaseExport;
use Illuminate\Support\Str;

class EmploymentInformationExport extends BaseExport
{
    protected function orderBy($column, $orderBy)
    {
    	$originalFunc = parent::orderBy($column, $orderBy);
    	$this->query->orderByField();

    	return $originalFunc;
    }
}
