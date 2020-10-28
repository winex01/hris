<?php

namespace App\Http\Requests;

use App\Http\Requests\CreateBloodTypeRequest;


class StoreBloodTypeRequest extends CreateBloodTypeRequest
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
            'blood_types'
        );
        
        return $rules;
    }
}
