<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use javcorreia\Wishlist\Models\Wishlist as BaseWishlist;

class Wishlist extends BaseWishlist
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

    public function donation()
    {
        return $this->hasOne(DonationRequest::class, 'id', 'item_id');
    }
}
