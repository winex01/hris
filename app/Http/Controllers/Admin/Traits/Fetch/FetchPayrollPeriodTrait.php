<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchPayrollPeriodTrait
{
    public function fetchPayrollPeriod()
    {
        return $this->fetch(\App\Models\PayrollPeriod::class);
    }

    public function fetchPayrollPeriodCollection()
    {
        return collect($this->fetchPayrollPeriod()->items());
    }
}