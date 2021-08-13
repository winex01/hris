<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchDepartmentTrait
{
    public function fetchDepartment()
    {
        return $this->fetch(\App\Models\Department::class);
    }
    
}