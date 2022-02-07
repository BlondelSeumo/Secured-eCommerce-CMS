<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Order extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function combined_order()
    {
        return $this->belongsTo(CombinedOrder::class);
    }

}
