<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralExport implements 
    FromQuery, 
    WithMapping,
    WithHeadings,
    ShouldAutoSize,
    WithCustomStartCell,
    WithStyles,
    WithProperties,
    WithEvents
{
    use Exportable;

    protected $model;
    protected $entries;
    protected $exportColumns;

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

        $tableColumns = getTableColumns($this->model->getTable());
        $tableColumns[] = 'created_at';
        
        $this->exportColumns = collect($tableColumns)
            ->filter(function ($value, $key) {
                return in_array($value, $this->exportColumns);
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
        
        return $this->model::query()->orderBy('created_at');
    }

    public function map($entry): array
    {
        $obj = [];
        foreach ($this->exportColumns as $col) {
            if (stringContains($col, '_id')) {
                $method = str_replace('_id', '', $col);
                if ($entry->{$method}) {
                    $obj[] = $entry->{$method}->name;                
                }
                continue;
            }
            $obj[] = $entry->{$col};                
        }

        return $obj;
    }

    public function headings(): array
    {
        $header = collect($this->exportColumns)->map(function ($item, $key) {
            $item = str_replace('_id', '', $item);
            $item = str_replace('_', ' ', $item);
            $item = ucwords($item);

            return $item;
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

}
