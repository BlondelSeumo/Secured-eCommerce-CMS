<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Wallet extends Model
{
    use SoftDeletes;

    public function user(){
    	return $this->belongsTo(User::class)->withTrashed();
    }
}
