<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;

    public function product(){
    	return $this->belongsTo(Product::class)->withTrashed();
    }

    public function category(){
    	return $this->belongsTo(Category::class);
    }
}
