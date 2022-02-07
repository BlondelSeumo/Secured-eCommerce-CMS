<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatThread extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
