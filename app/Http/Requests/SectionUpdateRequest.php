<?php

namespace App\Http\Requests;

use App\Http\Requests\SectionCreateRequest;

class SectionUpdateRequest extends SectionCreateRequest
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
        
        $rules['name'] = $this->uniqueRules($this->table);
        
        return $rules;
    }  
}
