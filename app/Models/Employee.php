<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use CrudTrait;
    use \App\Models\Traits\ImageTrait;
    use \App\Models\Traits\PersonTrait;
    use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Models\Traits\RevisionableInitTrait;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \App\Models\Traits\SoftDeletesInitTrait;

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
            (new self)->deleteImageFile($data);

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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
