<?php

namespace App\Http\Requests;

use App\Http\Requests\PersonalDataCreateRequest;

class PersonalDataStoreRequest extends PersonalDataCreateRequest
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
        
        $rules['employee_id'] = [
            'required',
            $this->uniqueRules('personal_datas')
        ];
        
        return $rules;
    }

  
}
