<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CreatePersonalDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'zip_code' => 'nullable|numeric',
            'birth_date' => 'nullable|date',
            'mobile_number' => 'nullable|numeric',
            'telepehone_number' => 'nullable|numeric',
            'personal_email' => 'nullable|email',
            'company_email' => 'nullable|email',
            'pagibig' => 'nullable|numeric',
            'philhealth' => 'nullable|numeric',
            'sss' => 'nullable|numeric',
            'date_applied' => 'nullable|date',
            'date_hired' => 'nullable|date',
            'employee_id' => 'required|unique:personal_datas',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
            'employee_id.required' => 'The employee field is required.',
            'employee_id.unique' => 'This employee has already have personal data.',
        ];
    }
}
