<?php 

namespace App\Models\Traits;


trait AttachmentTrait
{
	/*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function downloadAttachment() {
       
        return '<a href="'.url('storage/'.$this->attachment).'" download>Download</a>';

    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    // convert column/field name attachment to downloadable link
    public function setAttachmentAttribute($value)
    {
        $attribute_name = "attachment";
        $disk = "public";
        $destination_path = \Str::plural($attribute_name);

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

    // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }
}