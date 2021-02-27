<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CurrentEmployeeShiftScheduleScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereRaw('(
                employee_shift_schedules.employee_id,
                employee_shift_schedules.created_at
            ) = ANY(
                SELECT 
                    t2.employee_id,
                    max(t2.created_at)
                FROM employee_shift_schedules t2
                WHERE t2.effectivity_date <= ?
                GROUP BY t2.employee_id
        )', currentDate());
    }
}
