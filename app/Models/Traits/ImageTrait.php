<?php 

namespace App\Models\Traits;

/**
 * Use this if you want to use image type field of backpack
 */
trait ImageTrait
{
	/*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
	protected $attribute_name = 'img_url'; //getImgUrlAttribute()   
	protected $destination_path = 'images/photo';
	protected $disk = 'public';


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function deleteImage()
    {
        // use for mutator, delete image in Setting image or changing
        if ($this->image) {
            $attribute_name = $this->attribute_name;

            // remove 'storage/' from img path
            $imgPath = str_replace('storage/', '', $this->{$attribute_name});
            \Storage::disk($this->disk)->delete($imgPath);
            $this->image->delete();
        }
    }
    
    // NOTE:: if there are traits that override boots method override it on model
    // to avoid error
    public static function boot() 
    {
        parent::boot();

        static::deleted(function($data) {
            // delete image if force delete
            (new self)->deleteImageFile($data);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function image()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getImgUrlAttribute()
    {
        if (!$this->image) {
            return null; 
        }
        return 'storage/'.$this->image->url;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setImgAttribute($value){
        $destination_path = $this->destination_path;
        $disk = $this->disk;

        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            $this->deleteImage();
        }

        // if a base64 was sent, store it in the db
        if (\Str::startsWith($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value)->encode('jpg', 90);

            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';

            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());

            // 3. Delete the previous image, if there was one.
            $this->deleteImage();

            // 4. Save the public path to the database
            // but first, remove "public/" from the path, since we're pointing to it 
            // from the root folder; that way, what gets saved in the db
            // is the public URL (everything that comes after the domain name)
            $public_destination_path = \Str::replaceFirst('public/', '', $destination_path);
            // $this->attributes[$attribute_name] = $public_destination_path.'/'.$filename;
            
            $this->image()->save(
                new \App\Models\Image([
                    'url' => $public_destination_path.'/'.$filename
                ])
            );
        }

    }
}