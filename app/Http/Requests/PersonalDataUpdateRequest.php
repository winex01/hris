<?php

namespace App\Http\Requests;

use App\Http\Requests\PersonalDataCreateRequest;

class PersonalDataUpdateRequest extends PersonalDataCreateRequest
{
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules();
       
    }

  
}
