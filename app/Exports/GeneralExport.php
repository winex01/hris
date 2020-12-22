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
    }

    public function query()
    {
    	if ($this->entries) {
    		return $this->model::query()->whereIn('id', $this->entries);
    	}
        
        return $this->model::query();
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

        return $obj;
    }

    private function tableColumns()
    {

    }
}
