<?php 

namespace App\Models\Traits;


trait PersonTrait
{
	/*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function deletePerson($method, $data)
    {
        // if softDelete is enabled
        if ($data->soft_deleting) {
            if ($data->isForceDeleting()) {
                // delete polymorphic
                if ($data->{$method}()) {
                    $data->{$method}()->delete();
                }
            }
        }else {
            // if softDelete is not enabled then delete normally
            // delete polymorphic
            if ($data->{$method}()) {
                $data->{$method}()->delete();
            }
        }
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
        return $this->{$relation}()->update($data);
    }
}