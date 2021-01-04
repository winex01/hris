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
            'last_name'  => 'required|min:3|max:255',
            'first_name' => 'required|min:3|max:255',
            'badge_id'   => 'nullable|unique:employees',
            // personal data
            'zip_code'         => 'nullable|numeric',
            'birth_date'       => 'nullable|date',
            'mobile_number'    => 'nullable|'.phoneNumberRegex(),
            'telephone_number' => 'nullable|'.phoneNumberRegex(),
            'personal_email'   => 'nullable|email',
            'company_email'    => 'nullable|email',
            'pagibig'          => 'nullable|regex:/^[0-9\-]+$/',
            'philhealth'       => 'nullable|regex:/^[0-9\-]+$/',
            'sss'              => 'nullable|regex:/^[0-9\-]+$/',
            'tin'              => 'nullable|regex:/^[0-9\-]+$/',
            'date_applied'     => 'nullable|date',
            'date_hired'       => 'nullable|date',
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
