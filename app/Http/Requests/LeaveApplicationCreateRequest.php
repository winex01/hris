<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\LeaveApplicationCrudController;
use App\Http\Requests\FormRequest;

class LeaveApplicationCreateRequest extends FormRequest
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
        $creditUnits = new LeaveApplicationCrudController();
        $creditUnits = collect($creditUnits->creditUnitLists())->flip()->flatten()->toArray();

        $rules = [
            'employee_id'   => ['required', 'integer', $this->customUniqueRules()],
            'leave_type_id' => 'required|integer',
            'date'  => 'required|date', 
            'credit_unit' => ['required', $this->inArrayRules($creditUnits)],
        ];

        // NOTE:: employee leave credit validation is @withValidator($validator) method       

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Skip if any previous field was invalid.
            if ($validator->failed()) return;

            // employee leave credit validation
            if ($this->isEmployeeHasLeaveCredits() == false) {
                Validator::make($this->input(), [
                    'leave_credits' => 'required'
                ], $this->messages())->validate();
            }

        });
    }

    private function isEmployeeHasLeaveCredits()
    {
        $employee = modelInstance('LeaveCredit')
                    ->where('employee_id', request()->employee_id)
                    ->where('leave_type_id', request()->leave_type_id)
                    ->first();

        if ($employee) {
            if ( ($employee->leave_credit - request()->credit_unit) >= 0 ) {
                return true;
            }
        }

        return false;
    }

    protected function customUniqueRules()
    {
        return $this->uniqueRulesMultiple($this->getTable(), [
            'employee_id' => request()->employee_id,
            'date' => request()->date,
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
            'employee_id.unique' => 'Duplicate entry, The employee has already have leave on this date.',
            'leave_credits.required' => 'The employee doesn\'t have enough leave credits.',
        ];

        return collect($msg)->merge($appendMsg)->toArray();
    }
}
