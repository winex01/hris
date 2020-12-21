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

    public function __construct($model, $entries)
    {
    	$this->model = $model;

    	// checkbox id's
    	$this->entries = $entries;
    }

    public function query()
    {
    	if ($this->entries) {
    		return classInstance($this->model)::query()->whereIn('id', $this->entries);
    	}
        
        return classInstance($this->model)::query();
    }

    public function map($entry): array
    {
        // TODO:: export visibility column
        return [
            $entry->employee->full_name,
            $entry->company_name,
            $entry->award,
        ];
    }
}
