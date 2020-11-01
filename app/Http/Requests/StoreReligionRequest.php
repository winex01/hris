<?php

namespace App\Http\Requests;

use App\Http\Requests\CreateReligionRequest;

class StoreReligionRequest extends CreateReligionRequest
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
            'religions'
        );
        
        return $rules;
    }
}
