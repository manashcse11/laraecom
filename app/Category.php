<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**-------- Relationship ---------*/
    public function subcategories(){
        return $this->hasMany('App\Category', 'parent_id', 'id');
    }
    /**-------- User defined function ---------*/
    public function getCategoriesWithNestedChild(){
        return $this->where('parent_id', 0)->with('subcategories')->orderBy('name')->get();
    }
}
