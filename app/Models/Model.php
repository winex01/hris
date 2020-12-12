<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
	use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Models\Traits\RevisionableInitTrait;
	use \App\Models\Traits\SoftDeletesInitTrait;
	use \App\Models\Traits\AttachmentTrait;
    use \App\Models\Traits\FileTrait;

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function boot() 
    {
        parent::boot();

        static::deleted(function($data) {
            if ($data->attachment) {
                (new self)->deleteFileFromStorage($data, $data->attachment);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getModelAttribute()
    {   
        $class = get_class($this);
    	
        return str_replace('App\\Models\\', '', $class);
    }

}
