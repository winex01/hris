<?php

namespace App\Models;

use App\Models\Model;

class Employee extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \App\Models\Traits\ImageTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employees';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function boot() 
    {
        parent::boot();

        static::deleted(function($data) {
            if ($data->photo) {
                (new self)->deleteFileFromStorage($data, $data->photo);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function bloodType()
    {
        return $this->belongsTo(\App\Models\BloodType::class);
    }

    public function citizenship()
    {
        return $this->belongsTo(\App\Models\Citizenship::class);
    }

    public function civilStatus()
    {
        return $this->belongsTo(\App\Models\CivilStatus::class);
    }

    public function dependents()
    {
        return $this->hasMany(\App\Models\Dependent::class);
    }

    public function employmentInformation()
    {
        return $this->hasMany(\App\Models\EmploymentInformation::class);
    }

    public function familyDatas()
    {
        return $this->hasMany(\App\Models\FamilyData::class);
    }

    public function educationalBackgrounds()
    {
        return $this->hasMany(\App\Models\EducationalBackground::class);
    }

    public function gender()
    {
        return $this->belongsTo(\App\Models\Gender::class);
    }

    public function professionalOrgs()
    {
        return $this->hasMany(\App\Models\ProfessionalOrg::class);
    }

    public function medicalInformations()
    {
        return $this->hasMany(\App\Models\MedicalInformation::class);
    }

    public function religion()
    {
        return $this->belongsTo(\App\Models\Religion::class);
    }

    public function skillAndTalents()
    {
        return $this->hasMany(\App\Models\SkillAndTalent::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOrderByFullName($query)
    {
        return $query->orderBy('last_name')
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('badge_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute()
    {
        return $this->last_name.' '.$this->first_name.' '.$this->middle_name;
    }

    //alias of full_name
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    public function getFullNameWithBadgeAttribute()
    {
        return $this->full_name.' - ('.$this->badge_id.')';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setPhotoAttribute($value)
    {
        $attribute_name = 'photo';
        // or use your own disk, defined in config/filesystems.php
        $disk = 'public'; 
        // destination path relative to the disk above
        $destination_path = 'images/photo'; 

        $this->uploadImageToDisk($value, $attribute_name, $disk, $destination_path);
    }

}
