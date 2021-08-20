<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class PayrollPeriodCreateRequest extends FormRequest
{
    public function getTable()
    {
        return $this->setRequestTable(get_class($this));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $addRules = [
            'description'              => 'nullable|min:5',
            'year_month'               => 'required|min:7|max:7',
            'payroll_start'            => 'required|date',
            'payroll_end'              => 'required|date',
            'deduct_pagibig'           => 'required|boolean',
            'deduct_philhealth'        => 'required|boolean',
            'deduct_sss'               => 'required|boolean',
            'withholding_tax_basis_id' => 'required|numeric',
            'grouping_id'              => 'required|numeric',
            'is_last_pay'              => 'required|boolean',
        ];

        $rules = array_merge($rules, $addRules);

        return $rules;
    }
}
