<?php

namespace App\Http\Requests;

use App\Http\Requests\CreatePersonalDataRequest;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
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
            'last_name' => 'required|min:3|max:255',
            'first_name' => 'required|min:3|max:255',
            'badge_id' => 'nullable|unique:employees',
        ];

        $personalDataRequest = new CreatePersonalDataRequest;
        $personalDataRequest = $personalDataRequest->rules();

        $rules = array_merge($rules, $personalDataRequest);

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
