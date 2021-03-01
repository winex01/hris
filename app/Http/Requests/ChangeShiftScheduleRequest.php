<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class ChangeShiftScheduleRequest extends FormRequest
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
            'employee_id'       => 'required|integer',
            'date'              => 'required|date',
            'shift_schedule_id' => 'required|integer',
        ];
    }
}
