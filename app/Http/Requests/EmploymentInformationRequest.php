<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class EmploymentInformationRequest extends FormRequest
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
        return [
            'employee_id'      => 'required|numeric',
            'company'          => 'required',
            'location'         => 'required',
            'daysPerYear'      => 'required',
            'payBasis'         => 'required',
            'paymentMethod'    => 'required',
            'employmentStatus' => 'required',
            'jobStatus'        => 'required',
            'grouping'         => 'required',
            'basic_rate'       => 'required|numeric',
            'effectivity_date' => 'required|date',
        ];
    }

}
