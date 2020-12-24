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

    public function __construct($model, $entries, $exportColumns)
    {
    	$this->model = classInstance($model);
    	// checkbox id's
    	$this->entries = $entries;
        $this->exportColumns = $exportColumns;
        
        // dont include this columns in exports see at config/hris.php
        $this->exportColumns = collect($this->exportColumns)->diff(
            config('hris.dont_include_in_exports')
        )->toArray();

        $this->tableColumns = getTableColumnsWithDataType($this->model->getTable());
        $this->tableColumns['created_at'] = 'timestamp';
        
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
            if (stringContains($col, '_id')) {
                $method = str_replace('_id', '', $col);
                if ($entry->{$method}) {
                    $obj[] = $entry->{$method}->name;                
                }else {
                    $obj[] = $entry->{$col};                
                }
                continue;
            }

            if ($dataType == 'date') {
                $obj[] = Date::PHPToExcel($entry->{$col}); 
            }else {
                $obj[] = $entry->{$col};                
            }
        }


        return $obj;
    }

    public function headings(): array
    {
        $header = collect($this->exportColumns)->map(function ($dataType, $col) {
            $col = str_replace('_id', '', $col);
            $col = str_replace('_', ' ', $col);
            $col = ucwords($col);

            return $col;
        })->toArray();

        return $header;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the row as bold text.
            5    => ['font' => ['bold' => true]],
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
        $report = $this->model->getTable();
        $report = str_replace('_', ' ', $report);
        $report = ucwords($report);

        return [
            AfterSheet::class    => function(AfterSheet $event) use ($report) {
                $event->sheet->setCellValue('A2', $report);
                $event->sheet->setCellValue('A3', 'Generated: '. date('Y-m-d'));
            },
        ];
    }

    public function columnFormats(): array
    {
        // list of formats
        $formats = [
            'date'   => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'double' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];

        $data = [];
        $inc = 'A';
        foreach ($this->exportColumns as $col => $dataType) {
            if (array_key_exists($dataType, $formats)) {
                $data[$inc] = $formats[$dataType];
            }
            $inc++;
        }

        return $data;
    }

}
