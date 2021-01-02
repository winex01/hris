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
        $rules = [
            'last_name'  => 'required|min:3|max:255',
            'first_name' => 'required|min:3|max:255',
            'badge_id'   => 'nullable|unique:employees',
            // personal data
            'zip_code'         => 'nullable|numeric',
            'birth_date'       => 'nullable|date',
            'mobile_number'    => 'nullable|regex:/^([0-9\s\-\+\(\).]*)$/',
            'telephone_number' => 'nullable|regex:/^([0-9\s\-\+\(\).]*)$/',
            'personal_email'   => 'nullable|email',
            'company_email'    => 'nullable|email',
            'pagibig'          => 'nullable|regex:/^[0-9\-]+$/',
            'philhealth'       => 'nullable|regex:/^[0-9\-]+$/',
            'sss'              => 'nullable|regex:/^[0-9\-]+$/',
            'tin'              => 'nullable|regex:/^[0-9\-]+$/',
            'date_applied'     => 'nullable|date',
            'date_hired'       => 'nullable|date',
        ];

        // TODO:: fix
        // $familyDatas = (new \App\Http\Controllers\Admin\EmployeeCrudController)->familyDataTabs();
        // foreach ($familyDatas as $familyData) {
        //     $rules[$familyData.'_last_name']  = 'nullable|min:3|max:255';
        //     $rules[$familyData.'_first_name'] = 'nullable|min:3|max:255';
            
        //     $rules[$familyData.'_mobile_number']    = 'nullable|regex:/^([0-9\s\-\+\(\).]*)$/';
        //     $rules[$familyData.'_telephone_number'] = 'nullable|regex:/^([0-9\s\-\+\(\).]*)$/';
        //     $rules[$familyData.'_zip_code']         = 'nullable|numeric';
        //     $rules[$familyData.'_birth_date']       = 'nullable|date';
        //     $rules[$familyData.'_company_email']    = 'nullable|email';
        //     $rules[$familyData.'_personal_email']   = 'nullable|email';
        // }

        return $rules;
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
