<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Relationship
     */
    public function category(){
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    public function product_variations(){
        return $this->hasMany('App\ProductVariation');
    }

    /**
     * Scopes
     */
    public function scopeProductFilter($query, $filters){
        foreach($filters as $field => $value){
            if($field && $value && $field != "_token"){
                $query->where($field, $value);
            }
        }
        return $query;
    }
    /**
     * User defined functions
     */
    public function getProducts($filters){
        return $this->productFilter($filters)
            ->with('category')
            ->orderby('name')
            ->get();
    }
}
