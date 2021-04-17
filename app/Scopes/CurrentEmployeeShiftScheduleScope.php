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
                employee_shift_schedules.effectivity_date,
                employee_shift_schedules.created_at
            ) = ANY(
                SELECT 
                    t2.employee_id,
                    t2.effectivity_date,
                    MAX(t2.created_at)
                FROM employee_shift_schedules t2
                WHERE t2.effectivity_date <= ?
                AND t2.effectivity_date = (
                    SELECT MAX(t3.effectivity_date) FROM employee_shift_schedules t3 
                    WHERE t3.employee_id = t2.employee_id 
                    AND t3.effectivity_date <= ?
                )
                GROUP BY t2.employee_id, t2.effectivity_date
        )', [
            currentDate(),
            currentDate()
        ]);
    }
}
