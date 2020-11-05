<?php

namespace App\Http\Requests;

use App\Http\Requests\GenderCreateRequest;

class GenderStoreRequest extends GenderCreateRequest
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
            'genders'
        );
        
        return $rules;
    }
}
