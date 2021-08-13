<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchCompanyTrait
{
    public function fetchCompany()
    {
        return $this->fetch(\App\Models\Company::class);
    }
    
}