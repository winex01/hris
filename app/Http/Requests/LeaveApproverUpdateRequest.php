<?php

namespace App\Http\Requests;

use App\Http\Requests\LeaveApproverCreateRequest;

class LeaveApproverUpdateRequest extends LeaveApproverCreateRequest
{
    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
   public function rules()
   {
       $rules = parent::rules();
       
       $append = [
            'employee_id' => ['required', 'integer'], // override create request and remove unique, see store method in crud controller
        ];

      return collect($rules)->merge($append)->toArray();
   }
}
