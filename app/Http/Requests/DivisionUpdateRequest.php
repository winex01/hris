<?php

namespace App\Http\Requests;

use App\Http\Requests\DivisionCreateRequest;

class DivisionUpdateRequest extends DivisionCreateRequest
{
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        
        $rules['name'] = $this->uniqueRules(
            'divisions'
        );
        
        return $rules;
    }   
}
