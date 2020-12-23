<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;


class GeneralExport implements FromQuery, WithMapping
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
        foreach (getTableColumns($this->model->getTable()) as $col) {
            if (in_array($col, $this->exportColumns)) {
                $obj[] = $entry->{$col};                
            }
            // otherwise dont include
        }

        $obj[] = $entry->created_at;

        return $obj;
    }
}
