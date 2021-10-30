<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchDepartmentTrait
{
    public function fetchDepartment()
    {
        return $this->fetch(\App\Models\Department::class);
    }
    
}