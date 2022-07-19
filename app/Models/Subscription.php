<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $casts = [
        'created_at'  => 'datetime:m/d/Y H:i A',
        'updated_at' => 'datetime:m/d/Y H:i A',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /*public function getPriceAttribute($value) {
        return number_format($value,2,'.','');
    }*/
}
