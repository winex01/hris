<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class EducationalBackgroundRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id'      => 'required|integer',
            'educationalLevel' => 'required|integer',
            'school'           => 'required',
            'attachment'       => 'nullable|max:'.config('settings.hris_attachment_file_limit'),
        ];
    }
}
