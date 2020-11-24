<?php

namespace App\Http\Requests;

use App\Http\Requests\EmployeeCreateRequest;

class EmployeeUpdateRequest extends EmployeeCreateRequest
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

        $rules['badge_id'] = [
            $this->uniqueRules('employees'),
            'nullable',
        ];

        return $rules;
    }
}
