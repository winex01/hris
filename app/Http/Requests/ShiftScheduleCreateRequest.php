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

    	$workingHours = json_decode(request()->working_hours) ?? [];
        $row = 1;
        foreach ($workingHours as $wh) {
        	if (!property_exists($wh, 'start')) {
        		$append['working_hours_pair_'.$row.'_start'] = 'required';
        	}

        	if (!property_exists($wh, 'end')) {
        		$append['working_hours_pair_'.$row.'_end_'] = 'required';
        	}
        	
        	$row++;
        }

        return collect($rules)->merge($append)->toArray();
    }
}
