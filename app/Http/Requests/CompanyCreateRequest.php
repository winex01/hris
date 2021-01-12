<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CompanyCreateRequest extends FormRequest
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
            'name'              => 'required|min:1|max:255|unique:companies',
            'fax_number'        => 'nullable|'.phoneNumberRegex(),
            'mobile_number'     => 'nullable|'.phoneNumberRegex(),
            'telephone_number'  => 'nullable|'.phoneNumberRegex(),
            'pagibig_number'    => 'nullable|regex:/^[0-9\-]+$/',
            'philhealth_number' => 'nullable|regex:/^[0-9\-]+$/',
            'sss_number'        => 'nullable|regex:/^[0-9\-]+$/',
            'tax_id_number'     => 'nullable|regex:/^[0-9\-]+$/',
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
