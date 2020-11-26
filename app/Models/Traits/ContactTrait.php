<?php 

namespace App\Models\Traits;


trait ContactTrait
{
	/*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function father($data = null)
    {
        return $this->setContact('father', $data);
    }

    public function mother($data = null)
    {
        return $this->setContact('mother', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function contact()
    {
        return $this->morphOne('App\Models\Contact', 'contactable');
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
    public function getContact($relation)
    {
        return $this->contact()->where('relation', $relation)->first();
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setContact($relation, $data)
    {
        $contact = $this->getContact($relation);

        // get
        if (empty($data)) {
            return $contact;
        }

        // save
        if (empty($contact)) {
            $data['relation'] = $relation;

            return $this->contact()->save(
                new \App\Models\Contact($data)
            );
        }

        // update
        return $this->contact()->update($data);
    }
}