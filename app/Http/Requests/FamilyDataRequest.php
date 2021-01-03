<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class FamilyDataRequest extends FormRequest
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
            // 
            'employee_id'      => 'required|numeric',
            'last_name'        => 'nullable|min:3|max:255',
            'first_name'       => 'nullable|min:3|max:255',
            
            'mobile_number'    => 'nullable|'.phoneNumberRule(),
            'telephone_number' => 'nullable|'.phoneNumberRule(),
            
            'company_email'    => 'nullable|email',
            'personal_email'   => 'nullable|email',
            
            'birth_date'       => 'nullable|date',
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
