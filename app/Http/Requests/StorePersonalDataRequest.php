<?php

namespace App\Http\Requests;

use App\Http\Requests\CreatePersonalDataRequest;

class StorePersonalDataRequest extends CreatePersonalDataRequest
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
        
        $rules['employee_id'] = $this->uniqueRules(
            'personal_datas'
        );
        
        return $rules;
    }

  
}
