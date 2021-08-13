<?php 

namespace App\Http\Controllers\Admin\Traits;

trait FetchGenderTrait
{
    public function fetchGender()
    {
        return $this->fetch(\App\Models\Gender::class);
    }
}