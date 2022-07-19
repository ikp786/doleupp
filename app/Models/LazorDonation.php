<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class LazorDonation extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $casts = [
        'created_at'  => 'datetime:m/d/Y H:i A',
        'updated_at' => 'datetime:m/d/Y H:i A',
    ];

    /*public function getAmountAttribute($value) {
        return number_format($value,2,'.','');
    }

    public function getAmountForDonateAttribute($value) {
        return number_format($value,2,'.','');
    }*/

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
