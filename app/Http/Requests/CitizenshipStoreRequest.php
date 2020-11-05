<?php

namespace App\Http\Requests;

use App\Http\Requests\CitizenshipCreateRequest;

class CitizenshipStoreRequest extends CitizenshipCreateRequest
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
            'citizenships'
        );
        
        return $rules;
    }
}
