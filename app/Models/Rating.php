<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Rating extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $casts = [
        'created_at'  => 'datetime:m/d/Y H:i A',
        'updated_at' => 'datetime:m/d/Y H:i A',
    ];

    /*public function getRatingAttribute($value) {
        return number_format($value,1,'.','');
    }*/

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function donation()
    {
        return $this->hasOne(DonationRequest::class, 'id', 'donation_request_id');
    }
}
