<?php

namespace App\Exports;

use App\Exports\BaseExport;
use App\Models\AppraisalInterpretation;
use Illuminate\Database\Eloquent\Builder;

class PerformanceAppraisalExport extends BaseExport
{	
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

    public function dbColumnsWithDataType()
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
}
