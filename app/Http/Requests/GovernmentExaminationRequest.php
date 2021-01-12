<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class GovernmentExaminationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'required|numeric',
            'title'      => 'required|min:3|max:255',
            'date'       => 'required|date',
            'attachment' => 'nullable|max:'.config('settings.hris_attachment_file_limit'),
        ];
    }
}
