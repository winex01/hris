<?php

namespace App\Exports;

use App\Exports\BaseExport;
use App\Models\AppraisalInterpretation;
use Illuminate\Database\Eloquent\Builder;

class PerformanceAppraisalExport extends BaseExport
{	
    public function __construct($data)
    {
        parent::__construct($data);

        // dont include this columns in exports see at config/hris.php
        $this->exportColumns = collect($this->userFilteredColumns)->diff(
            config('hris.dont_include_in_exports')
        )->toArray();

        $this->tableColumns = $this->customColumnWithDataType();
        
        // add dataType - 'column' => 'dataType'
        $this->exportColumns = collect($this->tableColumns)
            ->filter(function ($dataType, $col) {
                return in_array($col, $this->exportColumns);
        })->toArray();
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
            }elseif ($filter == 'totalRatingBetween') {
                $item = AppraisalInterpretation::findOrFail($value);
            	$this->query->totalRatingBetween($item->rating_from, $item->rating_to);
            }else {
                // else as relationship
                $this->query->whereHas($filter, function (Builder $q) use ($value, $filter) {
                    $table = $q->getModel()->getTable();
                    $q->where($table.'.id', $value);
                });
            }
        }
    }

    // TODO:: fix this
    protected function orderBy($column, $orderBy)
    {   
        switch ($column) {
            case 'employee':
                $this->orderByEmployee($orderBy);
                break;

            // TODO:: fix appraisal_type order in datatable
            // TODO:: appraisal_type order in export here

            case 'appraisal_type':
                $column .= '_id';
                $this->query->orderBy($column, $orderBy);
                break;

            default:
                $this->query->orderBy($column, $orderBy);
                break;
        }// end switch
    }

    // override this if you want to modify what column shows in column dropdown with checkbox
    public static function exportColumnCheckboxes()
    {
        return [
            'employee_id',
            'date_evaluated',
            'appraisal_type_id',
            'appraiser_id',
            'accessor_individual_performance_rating',
            'accessor_job_competencies_rating',
            'accessor_organizational_competencies_rating',
            'accessor_total_rating',
            'accessor_interpretation',
        ];
    }

    private function customColumnWithDataType()
    {
        return [
            'employee_id'                                 => 'bigint',
            'date_evaluated'                              => 'date',
            'appraisal_type_id'                           => 'bigint',
            'appraiser_id'                                => 'bigint',
            'accessor_individual_performance_rating'      => 'double',
            'accessor_job_competencies_rating'            => 'double',
            'accessor_organizational_competencies_rating' => 'double',
            'accessor_total_rating'                       => 'double',
            'accessor_interpretation'                     => 'varchar',
        ];
    }

    public function headings(): array
    {
        $header = collect($this->exportColumns)->map(function ($dataType, $col) {
            $col = str_replace('accessor_', '', $col);
            return convertColumnToHumanReadable($col);
        })->toArray();

        return $header;
    }
}
