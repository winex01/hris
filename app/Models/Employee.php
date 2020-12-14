<?php

namespace App\Models;

use App\Models\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Employee extends Model
{
    use CrudTrait;
    use \App\Models\Traits\ImageTrait;
    use \App\Models\Traits\PersonTrait;
    use \Illuminate\Database\Eloquent\SoftDeletes;

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
            (new self)->deleteFileFromStorage($data, $data->img_url);

            // delete person relationship if employee is deleted 
            // (polymorphic so can't use delete cascade)
            $emp = new \App\Http\Controllers\Admin\EmployeeCrudController;
            foreach ( $emp->familyDataTabs() as $method ) {
                (new self)->deletePerson($emp->convertMethodName($method), $data);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function personalData()
    {
        return $this->hasOne('\App\Models\PersonalData');
    }

    public function emergencyContact($data = null)
    {
        return $this->setPerson('emergencyContact', $data);
    }

    public function father($data = null)
    {
        return $this->setPerson('father', $data);
    }

    public function mother($data = null)
    {
        return $this->setPerson('mother', $data);
    }

    public function spouse($data = null)
    {
        return $this->setPerson('spouse', $data);
    }

    public function supportingDocuments()
    {
        return $this->hasMany('\App\Models\SupportingDocument');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute()
    {
        return $this->last_name.' '.$this->first_name.' '.$this->middle_name;
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

}
