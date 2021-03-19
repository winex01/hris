<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\getActiveSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BaseExport implements 
    FromQuery, 
    WithMapping,
    WithHeadings,
    ShouldAutoSize,
    WithCustomStartCell,
    WithStyles,
    WithProperties,
    WithEvents,
    WithColumnFormatting
{
    use Exportable;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    protected $model;
    protected $entries;
    protected $exportColumns;
    protected $tableColumns;
    protected $userFilteredColumns;
    protected $rowStartAt = 5;
    protected $exportType;
    protected $filters;
    protected $currentTable;
    protected $currentColumnOrder;
    protected $query;
    protected $formats = [
        'date'    => NumberFormat::FORMAT_DATE_YYYYMMDD,
        'double'  => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        'decimal' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        'varchar' => NumberFormat::FORMAT_TEXT,
        'text'    => NumberFormat::FORMAT_TEXT,
    ];

    public function __construct($data)
    {
        $this->model               = classInstance($data['model']);
        $this->entries             = $data['entries'] ?? null; // checkbox id's
        $this->userFilteredColumns = $data['exportColumns'];
        $this->exportType          = $data['exportType'];
        $this->filters             = $data['filters'];
        $this->currentColumnOrder  = $data['currentColumnOrder'];
        $this->currentTable        = $this->model->getTable();    
        $this->query               = $this->model->query();
        $this->tableColumns        = $this->dbColumnsWithDataType();
        $this->exportColumns       = $this->setExportColumns();

        $this->filteredSoftDeletedItems();
    }

    // dont include items that has relationship soft deleted
    private function filteredSoftDeletedItems()
    {
        foreach ($this->hasRelationshipTo() as $temp) {
            $fk = $temp.'_id';
            if (array_key_exists($fk, $this->tableColumns)) {
                $this->query->has($temp);
            }
        }
    }

    public function query()
    {
        // if has filters
        if ($this->filters) {
            $this->applyActiveFilters();
        } 
        // if user check/select checkbox/entries
        // and order by check sequence
    	if ($this->entries) {
            $this->getOnlySelectedEntries();
    	}else {
            // if no entries selected
            // and user order the column desc/asc
            if ($this->currentColumnOrder != null) {
                $column = strtolower($this->currentColumnOrder['column']);
                $column = Str::snake($column);
                $orderBy = $this->currentColumnOrder['orderBy'];
                $this->orderBy($column, $orderBy);
            }else {
                 // if user didnt order column
                if (array_key_exists('employee_id', $this->tableColumns)) {
                    // if has relationship with employee then sort asc employee name
                    $this->orderByEmployee('asc');
                }elseif ($this->currentTable == 'employees') {
                    // if crud or table is employees then sort default
                    $this->query->orderByFullName();
                }elseif (array_key_exists('name', $this->tableColumns)) {
                    // if table has Name column
                    $this->query->orderBy('name', 'asc');
                }else {
                    // do nothing
                }
            }        
        }
        
        return $this->query->orderBy($this->currentTable.'.created_at');
    }

    public function map($entry): array
    {
        $obj = [];
        foreach ($this->exportColumns as $col => $dataType) {
            $value = null;
            if ($col == 'badge_id' && ($this->exportType == 'pdf' || $this->exportType == 'html')) {
                // NOTE:: prefend white space if export is PDF/HTML
                $value = ' '.$entry->{$col};
            }elseif (endsWith($col, '_id') && $entry->{relationshipMethodName($col)} ) {
                // if column has suffix _id,then it must be relationship
                $value = $entry->{relationshipMethodName($col)}->name; // app settings relationship                
            }elseif (startsWith($col, 'accessor_')) {
                $accessor = str_replace('accessor_', '', $col);
                $value = $entry->{$accessor};
            }else {
                $value = $entry->{$col};                
            }

            // if dataType
            if ($dataType == 'date') {
                $value = Date::PHPToExcel($value); 
            }elseif ($dataType == 'tinyint') {
                $value = booleanOptions()[$value];                
            }else {
                // do nothing
            }

            $obj[] = $value;
        }// end foreach

        return $obj;
    }

    public function headings(): array
    {
        $header = collect($this->exportColumns)->map(function ($dataType, $col) {
            $col = str_replace('accessor_', '', $col);
            $col = str_replace('_as_export', '', $col);
            return convertColumnToHumanReadable($col);
        })->toArray();

        return $header;
    }

    public function columnFormats(): array
    {
        $data = [];
        $inc = 'A';
        foreach ($this->exportColumns as $col => $dataType) {
            if (array_key_exists($dataType, $this->formats)) {
                $data[$inc] = $this->formats[$dataType];
            }
            $inc++;
        }

        return $data;
    }

    public function startCell(): string
    {
        return 'A'.$this->rowStartAt;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the row as bold text.
            $this->rowStartAt => ['font' => ['bold' => true]],
        ];
    }

    public function properties(): array
    {
        return [
            'creator' => auth()->user()->name,
        ];
    }

    public function registerEvents(): array
    {
        $report = convertColumnToHumanReadable(
            $this->model->model
        );
        
        return [
            AfterSheet::class    => function(AfterSheet $event) use ($report) {
                $event->sheet->setCellValue('A2', $report);
                $event->sheet->setCellValue('A3', 'Generated: '. currentDate());
            },
        ];
    }

    protected function applyActiveFilters()
    {
        foreach ($this->filters as $filter => $value) {
            if ($filter == 'persistent-table') {
                continue;
            }

            if (array_key_exists($filter, $this->tableColumns)) {
                // if filter is tablecolumn
                $this->query->where($this->currentTable.'.'.$filter, $value);
            }elseif (stringContains($filter, 'remove_scope_')) {
                // if filter is remove scope
                $scopeName = str_replace('remove_scope_', '', $filter);
                if (method_exists($this->query, $scopeName)) {
                    // local scope
                    $this->query->{$scopeName}();
                }else {
                    // global scope
                    $this->query->withoutGlobalScope(classInstance('\App\Scopes\\'.$scopeName, true));
                }
            }elseif (stringContains($filter, 'add_scope_')) {
                // if filter is add scope
                $scopeName = str_replace('add_scope_', '', $filter);
                if (method_exists($this->query, $scopeName)) {
                    // local scope
                    $this->query->{$scopeName}();
                }else {
                    // global scope
                    $this->query->withoutGlobalScope(classInstance('\App\Scopes\\'.$scopeName, true));
                }
            }elseif (stringContains($filter, 'date_range_filter_')) {
                // if filter is date
                $dates = json_decode($value);
                $column = str_replace('date_range_filter_', '', $filter);
                $this->query->whereBetween($this->currentTable.'.'.$column, [$dates->from, $dates->to]);
            }else {
                // else as relationship
                $this->query->whereHas($filter, function (Builder $q) use ($value, $filter) {
                    $table = $q->getModel()->getTable();
                    $q->where($table.'.id', $value);
                });
            }
        }
    }

    protected function getOnlySelectedEntries()
    {
        $ids_ordered = implode(',', $this->entries);

        $this->query->whereIn($this->currentTable.'.id', $this->entries)
            ->orderByRaw("FIELD($this->currentTable.id, $ids_ordered)");
    }

    protected function orderBy($column, $orderBy)
    {   
        if ($column == 'employee') {
            $this->orderByEmployee($orderBy);
        }elseif (method_exists($this->model, Str::camel($column))) {
            $joinTable = Str::plural($column);
            $this->query->join($joinTable, $joinTable.'.id', '=', $this->currentTable.'.'.$column.'_id')
                ->orderBy($joinTable.'.name', $orderBy);  
        }else {
            $this->query->orderBy($column, $orderBy);
        }
    }

    protected function orderByEmployee($column_direction = 'asc')
    {
        $this->query->join('employees', 'employees.id', '=', $this->currentTable.'.employee_id')
            ->orderBy('employees.last_name', $column_direction)
            ->orderBy('employees.first_name', $column_direction)
            ->orderBy('employees.middle_name', $column_direction)
            ->orderBy('employees.badge_id', $column_direction);   
    }

    protected function setExportColumns()
    {
        // dont include this columns in exports see at config/hris.php
        $data = collect($this->userFilteredColumns)->diff(
            config('hris.dont_include_in_exports')
        )->toArray();
        
        // add dataType - 'column' => 'dataType'
        $data = collect($this->tableColumns)
            ->filter(function ($dataType, $col) use ($data) {
                return in_array($col, $data);
        })->toArray();
    
        return $data;
    }

    public function dbColumnsWithDataType()
    {
        return getTableColumnsWithDataType($this->model->getTable());
    }

    // override this if you want to modify what column shows in column dropdown with checkbox
    public static function exportColumnCheckboxes()
    {
        return [
            // 
        ];
    }

    // declare if you want to idenfy which checkbox is check on default
    public static function checkOnlyCheckbox()
    {
        return [
            // 
        ];
    }
}
