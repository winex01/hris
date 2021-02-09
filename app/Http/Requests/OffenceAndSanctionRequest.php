<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class OffenceAndSanctionRequest extends FormRequest
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
        return [];
        // return [
        //     'employee_id'           => 'required|integer',
        //     'date_issued'           => 'required|date',
        //     'offenceClassification' => 'required|integer',
        //     'gravityOfSanction'     => 'required|integer',
        //     'attachment'            => 'nullable|max:'.config('settings.hris_attachment_file_limit'),
        // ];

        return [
            'employee_id'           => 'required|integer|unique:'.$this->getTable().',employee_id,offence_classification_id,gravity_of_sanction_id',
            'date_issued'           => 'required|date',
            'offenceClassification' => 'required|integer|unique:'.$this->getTable().',employee_id,offence_classification_id,gravity_of_sanction_id',
            'gravityOfSanction'     => 'required|integer|unique:'.$this->getTable().',employee_id,offence_classification_id,gravity_of_sanction_id',
            'attachment'            => 'nullable|max:'.config('settings.hris_attachment_file_limit'),
        ];
    }
}
