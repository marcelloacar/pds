<?php

namespace App\Shop\Offers;

use Illuminate\Database\Eloquent\Model;
use \Storage;

class Offer extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover',
        'status'
    ];


    
   /**
     * Get Cover URL
     *
     * @param  string  $value
     * @return string
     */
    public function getCoverAttribute($value)
    {
        if(strpos($value, 'http') !== false)
            return  $value;

        return  Storage::url($value);
    }
}
