<?php 

namespace App\Traits;


trait ImageTrait
{
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function deleteImage($attribute_name, $disk = 'public')
    {
        if ($this->image) {
            // remove storage/ from img path
            $imgPath = str_replace('storage/', '', $this->{$attribute_name});
            \Storage::disk($disk)->delete($imgPath);
            $this->image->delete();
        }
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
            return null; //TODO:: add default photo
        }
        return 'storage/'.$this->image->url;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setImgAttribute($value, $destination_path = 'images/photo', $disk = 'public'){
        $attribute_name = 'img_url'; //getPhotoUrlAttribute()   

        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            $this->deleteImage($attribute_name);
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
            $this->deleteImage($attribute_name);

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