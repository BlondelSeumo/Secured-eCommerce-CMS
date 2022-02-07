<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
	use SoftDeletes;
	
	public function product_taxes() {
        return $this->hasMany(ProductTax::class);
    }
}
