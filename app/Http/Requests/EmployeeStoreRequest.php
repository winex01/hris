<?php

namespace App\Http\Requests;

use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\PersonalDataStoreRequest;

class EmployeeStoreRequest extends EmployeeCreateRequest
{
    use \App\Traits\CrudExtendTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules['badge_id'] = $this->uniqueRules(
            'employees'
        );

        $personalDataRequest = new PersonalDataStoreRequest;
        $personalDataRequest = $personalDataRequest->rules();

        $rules = array_merge($rules, $personalDataRequest);

        return $rules;
    }
}
