<?php

namespace App\Exports;

use App\Exports\BaseExport;
class LeaveApproverExport extends BaseExport
{
    public function __construct($data)
    {
        parent::__construct($data);

        $this->setWrapText = true;
    }

    protected function changeColumnValue($col, $value)
    {
    	$value = strtolower($value);

        if ($col == 'approvers') {
            return jsonToArrayImplode($value, 'employee_name', ", \n");
        }

        return $value;
    }
}
