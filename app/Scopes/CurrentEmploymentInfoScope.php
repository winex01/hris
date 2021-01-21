<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CurrentEmploymentInfoScope implements Scope
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
            employment_informations.employee_id, 
            employment_informations.field_name, 
            employment_informations.created_at) = ANY(
                SELECT 
                    t2.employee_id,
                    t2.field_name,
                    MAX(t2.created_at)
                FROM employment_informations t2
                WHERE t2.effectivity_date <= ?
                GROUP BY t2.employee_id, t2.field_name
            )', date('Y-m-d'));
    }
}
