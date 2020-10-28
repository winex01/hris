<?php

namespace App\Http\Requests;

use App\Http\Requests\CreateEmployeeRequest;

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

        return $rules;
    }
}
