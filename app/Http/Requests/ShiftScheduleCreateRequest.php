<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class ShiftScheduleCreateRequest extends FormRequest
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
        $rules = parent::rules();

        if ( (bool)request()->open_time) {
            return $rules;
        }

        $append = [
            'working_hours'  => 'required|json',
            'overtime_hours' => 'nullable|json',
        ];

        // if json wh is empty then override it to null to activate validation
        if (request()->working_hours == '[{}]') {
            request()->merge([
                'working_hours' => null,
            ]);
        }else {
            // 
            $workingHours = json_decode(request()->working_hours);
            foreach ($workingHours ?? [] as $wh) {
                if (!property_exists($wh, 'start') || !property_exists($wh, 'end')) {
                    $append['wh_start_end_field'] = 'required';
                }
            }
        }

        // overtime validation must have start and end
        if (request()->overtime_hours != '[{}]') {
            $overtimeHours = json_decode(request()->overtime_hours);
            foreach ($overtimeHours ?? [] as $ot) {
                if (!property_exists($ot, 'start') || !property_exists($ot, 'end')) {
                    $append['ot_start_end_field'] = 'required';
                }
            }
        }


        return collect($rules)->merge($append)->toArray();
    }

    public function messages()
    {
        $msg = parent::messages();

        $append = [
            'wh_start_end_field.required' => 'The start and end field of working hours is required.',
            'ot_start_end_field.required' => 'The start and end field of overtime hours is required.',
        ];

        return collect($msg)->merge($append)->toArray();
    }
}
