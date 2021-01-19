<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class EmploymentInformationRequest extends FormRequest
{
    private $fields;

    public function __construct()
    {
        $cont = new \App\Http\Controllers\Admin\EmploymentInformationCrudController;
        $this->fields = array_merge($cont->selectFields(), $cont->inputFields());
    }

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
        
        $rules = [];
        foreach ($this->fields as $field) {
            $rules[$field] = 'nullable|numeric';
        }

        $rules = collect($rules)->merge([
            'employee_id'       => 'required|numeric',
            'COMPANY'           => 'required|numeric',
            'LOCATION'          => 'required|numeric',
            'DAYS_PER_YEAR'     => 'required|numeric',
            'PAY_BASIS'         => 'required|numeric',
            'PAYMENT_METHOD'    => 'required|numeric',
            'EMPLOYMENT_STATUS' => 'required|numeric',
            'JOB_STATUS'        => 'required|numeric',
            'GROUPING'          => 'required|numeric',
            'BASIC_RATE'        => 'required|numeric',
            'effectivity_date'  => 'required|date',
        ])->toArray();

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $msg = [];
        foreach ($this->fields as $field) {
            $msg[$field.'.required'] = 'The '.str_replace('_', ' ', strtolower($field)).' field is required.';
        }

        return $msg;
    }

}