<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App;

class Offer extends Model
{
    use SoftDeletes;

    public function offer_products()
    {
        return $this->hasMany(OfferProduct::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class,'offer_products');
    }
}
