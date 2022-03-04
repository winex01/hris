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

        $rules = [
            // 'employee_id'      => ['required', 'integer', $this->customUniqueRules()],
            'employee_id'      => ['required', 'integer'],
            'effectivity_date'  => 'required|date|after_or_equal:'.currentDate(),
            'approvers'        => 'nullable|json',
            'approvers.*'      => 'numeric',
        ];

        return $rules;
    }

    // TODO:: fix wrong duplicate entry validation. allow user to update multiple times.
    protected function customUniqueRules()
    {
        return $this->uniqueRulesMultiple($this->getTable(), [
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
            'employee_id.unique' => trans('lang.validation_duplicate_employee_date'),
            'approvers.*.numeric' => 'The selected approver is invalid.',
        ];

        return collect($msg)->merge($appendMsg)->toArray();
    }
}
