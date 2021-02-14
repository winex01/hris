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

        $append = [
            'working_hours' => 'required',
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
                    $append['start_end_field'] = 'required';
                }
            }
        }

        // TODO:: validation for overtime hours must have both start and end

        return collect($rules)->merge($append)->toArray();
    }

    public function messages()
    {
        $msg = parent::messages();

        $append = [
            'start_end_field.required' => 'The start and end field of working hours is required.',
        ];

        return collect($msg)->merge($append)->toArray();
    }
}
