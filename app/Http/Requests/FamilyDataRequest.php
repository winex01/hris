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
            'employee_id'    => 'required|numeric',
            'familyRelation' => 'required|numeric',
            'last_name'      => 'required|min:3|max:255',
            'first_name'     => 'required|min:3|max:255',
            
            'mobile_number'      => 'nullable|'.phoneNumberRegex(),
            'telephone_number'   => 'nullable|'.phoneNumberRegex(),
            
            'company_email'      => 'nullable|email',
            'personal_email'     => 'nullable|email',
            
            'birth_date'         => 'nullable|date',
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
