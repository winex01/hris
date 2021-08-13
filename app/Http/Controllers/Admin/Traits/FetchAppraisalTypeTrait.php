<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchAppraisalTypeTrait
{
    public function fetchAppraisalType()
    {
        return $this->fetch(\App\Models\AppraisalType::class);
    }
}