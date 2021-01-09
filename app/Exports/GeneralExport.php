<?php

namespace App\Exports;

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

class GeneralExport implements 
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

    protected $model;
    protected $entries;
    protected $exportColumns;
    protected $tableColumns;
    protected $userFilteredColumns;
    protected $rowStartAt = 5;
    protected $exportType;
    protected $formats = [
        'date'    => NumberFormat::FORMAT_DATE_YYYYMMDD,
        'double'  => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        'varchar' => NumberFormat::FORMAT_TEXT,
        'text'    => NumberFormat::FORMAT_TEXT,
    ];

    public function __construct($data)
    {
        $this->model               = classInstance($data['model']);
        $this->entries             = $data['entries']; // checkbox id's
        $this->userFilteredColumns = $data['exportColumns'];
        $this->exportType          = $data['exportType'];
        
        // dont include this columns in exports see at config/hris.php
        $this->exportColumns = collect($this->userFilteredColumns)->diff(
            config('hris.dont_include_in_exports')
        )->toArray();

        $this->tableColumns = $this->dbColumnsWithDataType();
        
        $this->exportColumns = collect($this->tableColumns)
            ->filter(function ($dataType, $col) {
                return in_array($col, $this->exportColumns);
        })->toArray();

    }

    public function query()
    {
    	if ($this->entries) {
            $ids_ordered = implode(',', $this->entries);

    		return $this->model::query()
                ->whereIn('id', $this->entries)
                ->orderByRaw("FIELD(id, $ids_ordered)");
    	}
        
        // if has relationship with employee and no entries selected
        if (array_key_exists('employee_id', $this->tableColumns)) {
            $currentTable = $this->model->getTable();
            $column_direction = 'ASC';
            return $this->model::query()
                ->join('employees', 'employees.id', '=', $currentTable.'.employee_id')
                ->orderBy('employees.last_name', $column_direction)
                ->orderBy('employees.first_name', $column_direction)
                ->orderBy('employees.middle_name', $column_direction)
                ->orderBy('employees.badge_id', $column_direction);
        }

        return $this->model::query()->orderBy('created_at');
    }

    public function map($entry): array
    {
        $obj = [];
        foreach ($this->exportColumns as $col => $dataType) {
            if ($col == 'badge_id' && ($this->exportType == 'pdf' || $this->exportType == 'html')) {
                // NOTE:: prefend white space if export is PDF/HTML
                $obj[] = ' '.$entry->{$col};
                continue;
            }

            if (stringContains($col, '_id')) {
                $method = relationshipMethodName($col);
                if ($entry->{$method}) {
                    $obj[] = $entry->{$method}->name;                
                }else {
                    $obj[] = $entry->{$col};                
                }
                continue;
            }

            if ($dataType == 'date') {
                $obj[] = Date::PHPToExcel($entry->{$col}); 
            }elseif ($dataType == 'tinyint') {
                $obj[] = booleanOptions()[$entry->{$col}];                
            }else {
                $obj[] = $entry->{$col};                
            }
        }// end foreach


        return $obj;
    }

    public function headings(): array
    {
        $header = collect($this->exportColumns)->map(function ($dataType, $col) {
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
        $report = convertToTitle(
            $this->model->getTable()
        );
        
        return [
            AfterSheet::class    => function(AfterSheet $event) use ($report) {
                $event->sheet->setCellValue('A2', $report);
                $event->sheet->setCellValue('A3', 'Generated: '. date('Y-m-d'));
            },
        ];
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
