<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class DonationPayment extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $casts = [
        'created_at'  => 'datetime:m/d/Y H:i A',
        'updated_at' => 'datetime:m/d/Y H:i A',
    ];

    /*public function getAmountAttribute($value) {
        return number_format($value,2,'.','');
    }*/

    public function donation_request()
    {
        return $this->hasOne(DonationRequest::class, 'id', 'donation_request_id');
    }
}
