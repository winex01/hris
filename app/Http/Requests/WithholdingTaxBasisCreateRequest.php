<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class WithholdingTaxBasisCreateRequest extends FormRequest
{
    public function getTable()
    {
        return $this->setRequestTable(get_class($this));
    }
}
