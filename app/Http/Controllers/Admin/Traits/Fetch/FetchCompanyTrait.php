<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchCompanyTrait
{
    public function fetchCompany()
    {
        return $this->fetch(\App\Models\Company::class);
    }
    
}