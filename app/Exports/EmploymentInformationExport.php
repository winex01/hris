<?php

namespace App\Exports;

use App\Exports\BaseExport;
use Illuminate\Support\Str;

class EmploymentInformationExport extends BaseExport
{
    protected function orderByAddOns()
    {
    	$this->query->orderByField();
    }
}
