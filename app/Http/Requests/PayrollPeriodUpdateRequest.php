<?php

namespace App\Http\Requests;

use App\Http\Requests\PayrollPeriodCreateRequest;
use Illuminate\Validation\Rule;

class PayrollPeriodUpdateRequest extends PayrollPeriodCreateRequest
{
   /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
   public function rules()
   {
       $rules = parent::rules();
       
       $rules['name'] = $this->uniqueRules($this->getTable());
       
       $append = [
            'grouping_id' => [
                'required', 'numeric',
                 Rule::unique('payroll_periods')->where(function ($query) {
                    return $query
                        ->where('grouping_id', request()->grouping_id)
                        ->where('status', 1)
                        ->whereNull('deleted_at'); // ignore softDeleted
                 })->ignore(request()->id)
            ],
        ];

      return collect($rules)->merge($append)->toArray();
   }
}
