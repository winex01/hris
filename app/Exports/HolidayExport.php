<?php

namespace App\Exports;

use App\Exports\BaseExport;

class HolidayExport extends BaseExport
{
    // override this if you want to modify what column shows in column dropdown with checkbox
    public static function exportColumnCheckboxes()
    {
        return array_merge(
        	getTableColumns('holidays'),
        	['accessor_locations_as_export']
        );
    }

    public function dbColumnsWithDataType()
    {
        return array_merge(
            getTableColumnsWithDataType('holidays'),
            ['accessor_locations_as_export' => 'varchar']
        );
    }
}
