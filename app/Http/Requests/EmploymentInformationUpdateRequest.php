<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class EmploymentInformationUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id'         => 'required|numeric',
            'field_name'          => 'required',
            request()->field_name => 'required|numeric',
            'effectivity_date'    => 'required|date|after_or_equal:'.date('Y-m-d'),
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $field = request()->field_name;
        $field = str_replace('_', ' ', strtolower($field));
        
        return [
            //
            request()->field_name.'.required' => 'The '.$field.' field is required.',
        ];
    }


}
