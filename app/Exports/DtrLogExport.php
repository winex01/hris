<?php

namespace App\Exports;

use App\Exports\BaseExport;

class DtrLogExport extends BaseExport
{
    public static function exportColumnCheckboxes()
    {   
        return [
            'employee_id',
            'accessor_date',
            'accessor_time',
            'dtr_log_type_id',
            'description',
        ];
    }

    public function dbColumnsWithDataType()
    {
        return [
            'employee_id'     => 'bigint',
            'accessor_date'   => 'date',
            'accessor_time'   => 'varchar',
            'dtr_log_type_id' => 'bigint',
            'description'     => 'text',
        ];
    }

    protected function orderByCurrentColumnOrder($col, $direction)
    {
        if ($col == 'date' || $col == 'time') {
            $col = 'log';
        }

        $this->orderBy($col, $direction);
    }

}