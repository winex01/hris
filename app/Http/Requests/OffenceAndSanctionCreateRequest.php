<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class OffenceAndSanctionCreateRequest extends FormRequest
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
            'employee_id' => [
                'required', 'integer',
                 Rule::unique('offence_and_sanctions')->where(function ($query) {
                    return $query
                        ->where('employee_id', request()->employee_id)
                        ->where('offence_classification_id', request()->offenceClassification)
                        ->where('gravity_of_sanction_id', request()->gravityOfSanction)
                        ->whereNull('deleted_at'); // ignore softDeleted
                 })
            ],
            'date_issued'           => 'required|date',
            'offenceClassification' => 'required|integer',
            'gravityOfSanction'     => 'required|integer',
            'attachment'            => 'nullable|max:'.config('settings.hris_attachment_file_limit'),
        ];
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
            'employee_id.unique' => 'Duplicate entry, The employee has already have this offence classification with this gravity sanction.',
        ];

        return collect($msg)->merge($appendMsg)->toArray();
    }
}
