<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchAppraisalTypeTrait
{
    public function fetchAppraisalType()
    {
        return $this->fetch(\App\Models\AppraisalType::class);
    }
}