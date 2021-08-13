<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchDaysperyearTrait
{
    public function fetchDaysperyear()
    {
        return $this->fetch([
            'model' => \App\Models\DaysPerYear::class,
            'searchable_attributes' => ['days_per_year', 'days_per_week', 'hours_per_day']
        ]);
    }
    
}