<?php

namespace App\Models;

use App\Models\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class PersonalData extends Model
{
    use CrudTrait;
    use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Models\Traits\RevisionableInitTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'personal_datas';
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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function employee()
    {
        return $this->belongsTo('\App\Models\Employee');
    }

    public function gender()
    {
        return $this->belongsTo('\App\Models\Gender');
    }

    public function civilStatus()
    {
        return $this->belongsTo('\App\Models\CivilStatus');
    }

    public function citizenship()
    {
        return $this->belongsTo('\App\Models\Citizenship');
    }

    public function religion()
    {
        return $this->belongsTo('\App\Models\Religion');
    }

    public function bloodType()
    {
        return $this->belongsTo('\App\Models\BloodType');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeInfo($query)
    {
        return $query->with(
            'gender',
            'civilStatus',
            'citizenship',
            'religion',
            'bloodType',
        );
    }
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
