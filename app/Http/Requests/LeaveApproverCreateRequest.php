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
            'effectivity_date'  => 'required|date|after_or_equal:'.currentDate(),
        ];
    }

    protected function customUniqueRules()
    {
        return $this->uniqueRulesMultiple($this->getTable(), [
            'level'            => request()->level,
            'approver_id'      => request()->approver_id,
            'effectivity_date' => request()->effectivity_date,
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
            'employee_id.unique'   => 'Duplicate entry. Edit the existing item instead.',
            'approver_id.required' => 'The approver field is required.',
        ];

        return collect($msg)->merge($appendMsg)->toArray();
    }
}
// TODO:: add validation that only accepts 1 and .5