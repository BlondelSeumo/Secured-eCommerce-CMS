<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App;

class Brand extends Model
{
    use SoftDeletes;
    protected $with = ['brand_translations'];

    public function getTranslation($field = '', $lang = false){
		$lang = $lang == false ? App::getLocale() : $lang;
		$brand_translation = $this->brand_translations->where('lang', $lang)->first();
		return $brand_translation != null ? $brand_translation->$field : $this->$field;
    }

    public function brand_translations(){
    	return $this->hasMany(BrandTranslation::class);
    }

}
