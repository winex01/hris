<?php 

namespace App\Models\Traits;


trait PersonTrait
{
	/*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function father($data = null)
    {
        return $this->setPerson('father', $data);
    }

    public function mother($data = null)
    {
        return $this->setPerson('mother', $data);
    }

    public function emergencyContact($data = null)
    {
        return $this->setPerson('emergency_contact', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function person()
    {
        return $this->morphOne('App\Models\Person', 'personable');
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
    public function getPerson($relation)
    {
        return $this->person()->where('relation', $relation)->first();
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setPerson($relation, $data)
    {
        $person = $this->getPerson($relation);

        // get
        if (empty($data)) {
            return $person;
        }

        // save
        if (empty($person)) {
            $data['relation'] = $relation;

            return $this->person()->save(
                new \App\Models\Person($data)
            );
        }

        // update
        return $this->person()->update($data);
    }
}