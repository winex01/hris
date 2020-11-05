<?php

namespace App\Http\Requests;

use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\StorePersonalDataRequest;

class StoreEmployeeRequest extends CreateEmployeeRequest
{
    use \App\Traits\RulesRequestTrait;

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

        $personalDataRequest = new StorePersonalDataRequest;
        $personalDataRequest = $personalDataRequest->rules();

        $rules = array_merge($rules, $personalDataRequest);

        return $rules;

        return $rules;
    }
}
