<?php 

namespace App\Http\Controllers\Admin\Traits;

/**
 * 
 */
trait FetchModelTrait
{
    public function fetchBloodType()
    {
        return $this->fetch(\App\Models\BloodType::class);
    }

    public function fetchCitizenship()
    {
        return $this->fetch(\App\Models\Citizenship::class);
    }

    public function fetchCivilStatus()
    {
        return $this->fetch(\App\Models\CivilStatus::class);
    }

    public function fetchEducationalLevel()
    {
        return $this->fetch(\App\Models\EducationalLevel::class);
    }

    public function fetchFamilyRelation()
    {
        return $this->fetch(\App\Models\FamilyRelation::class);
    }

    public function fetchGender()
    {
        return $this->fetch(\App\Models\Gender::class);
    }
    
    public function fetchReligion()
    {
        return $this->fetch(\App\Models\Religion::class);
    }
}