<?php

namespace App\Http\Requests;

use App\Http\Requests\LocationCreateRequest;

class LocationUpdateRequest extends LocationCreateRequest
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
            'locations'
        );
        
        return $rules;
    }
}
