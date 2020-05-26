<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'size_id', 'color_id', 'in_stock'
    ];

    /**
     * Relationship
     */
    public function product(){
        return $this->belongsTo('App\Product');
    }

}
