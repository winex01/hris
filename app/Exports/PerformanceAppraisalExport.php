<?php

namespace App\Exports;

use App\Exports\GeneralExport;
use App\Models\AppraisalInterpretation;
use Illuminate\Database\Eloquent\Builder;

class PerformanceAppraisalExport extends GeneralExport
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

    // TODO:: columns export
}
