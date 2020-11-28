<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeCreateRequest extends FormRequest
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
            'last_name' => 'required|min:3|max:255',
            'first_name' => 'required|min:3|max:255',
            'badge_id' => 'nullable|unique:employees',
            // personal data
            'zip_code' => 'nullable|numeric',
            'birth_date' => 'nullable|date',
            'mobile_number' => 'nullable|numeric',
            'telephone_number' => 'nullable|numeric',
            'personal_email' => 'nullable|email',
            'company_email' => 'nullable|email',
            'pagibig' => 'nullable|numeric',
            'philhealth' => 'nullable|numeric',
            'sss' => 'nullable|numeric',
            'tin' => 'nullable|numeric',
            'date_applied' => 'nullable|date',
            'date_hired' => 'nullable|date',
            // emergency contact
            'emergency_contact_last_name' => 'nullable|min:3|max:255',
            'emergency_contact_first_name' => 'nullable|min:3|max:255',
            'emergency_contact_mobile_number' => 'nullable|numeric',
            'emergency_contact_telephone_number' => 'nullable|numeric',
            'emergency_contact_zip_code' => 'nullable|numeric',
            'emergency_contact_company_address' => 'nullable|email',
            'emergency_contact_birth_date' => 'nullable|date',
            'emergency_contact_company_email' => 'nullable|email',
            'emergency_contact_personal_email' => 'nullable|email',

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
        ];
    }
}
