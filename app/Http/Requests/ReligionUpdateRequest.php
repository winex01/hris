<?php

namespace App\Http\Requests;

use App\Http\Requests\ReligionCreateRequest;

class ReligionUpdateRequest extends ReligionCreateRequest
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
        
        $rules['name'] = $this->uniqueRules(
            'religions'
        );
        
        return $rules;
    }
}
