<?php

namespace App\Http\Requests;

use App\Http\Requests\CivilStatusCreateRequest;

class CivilStatusStoreRequest extends CivilStatusCreateRequest
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

        $rules['name'] = $this->uniqueRules(
            'civil_statuses'
        );

        return $rules;
    }
}
