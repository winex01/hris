<?php

namespace App\Http\Requests;

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

        return [
            'employee_id'   => ['required', 'integer', $this->customUniqueRules()],
            'leave_type_id' => 'required|integer',
            'credit_unit' => ['required', $this->inArrayRules($creditUnits)],
        ];
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
        ];

        return collect($msg)->merge($appendMsg)->toArray();
    }
}
