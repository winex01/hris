<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class LeaveApproverCreateRequest extends FormRequest
{
    public function getTable()
    {
        return $this->setRequestTable(get_class($this));
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id'   => ['required', 'integer', $this->customUniqueRules()],
            'level' => ['required', 
                $this->inArrayRules(explode(',', config('appsettings.approver_level_lists')))
            ],
            'approver_id' => 'required|integer',
        ];
    }

    protected function customUniqueRules()
    {
        return $this->uniqueRulesMultiple($this->getTable(), [
            'employee_id' => request()->employee_id,
            'level' => request()->level,
        ]);
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $msg = parent::messages();
        
        $appendMsg = [
            'employee_id.unique' => 'Duplicate entry, The employee has already have this leave level.',
        ];

        return collect($msg)->merge($appendMsg)->toArray();
    }
}
