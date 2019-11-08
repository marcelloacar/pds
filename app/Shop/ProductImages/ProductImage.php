<?php

namespace App\Shop\ProductImages;

use App\Shop\Products\Product;
use Illuminate\Database\Eloquent\Model;
use \Storage;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'src'
    ];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get Images URL
     *
     * @param  string  $value
     * @return string
     */
    public function getSrcAttribute($value)
    {
        if(!strpos($value, 'http'))
            return  Storage::url($value);

        return  $value;
    }
}
