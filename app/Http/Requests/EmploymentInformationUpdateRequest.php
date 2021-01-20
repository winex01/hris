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
            'effectivity_date' => 'required|date',
            'field_name'       => 'required',
            'new_field_value'  => 'required|numeric',
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
            'new_field_value.required' => 'The '.$field.' field is required.',
        ];
    }


}
