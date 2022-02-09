<?php 

namespace App\Http\Controllers\Admin\Traits\Fetch;

trait FetchGenderTrait
{
    public function fetchGender()
    {
        return $this->fetch(\App\Models\Gender::class);
    }
}