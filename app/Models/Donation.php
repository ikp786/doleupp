<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Donation extends Model
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

    public function getAmountFromWalletAttribute($value) {
        return number_format($value,2,'.','');
    }

    public function getAdminCommissionAttribute($value) {
        return number_format($value,2,'.','');
    }*/

    public function donation_by()
    {
        return $this->hasOne(User::class, 'id', 'donation_by');
    }

    public function donation_to()
    {
        return $this->hasOne(User::class, 'id', 'donation_to');
    }

    public function donation_by_user()
    {
        return $this->hasOne(User::class, 'id', 'donation_by');
    }

    public function donation_to_user()
    {
        return $this->hasOne(User::class, 'id', 'donation_to');
    }

    public function donation_request()
    {
        return $this->hasOne(DonationRequest::class, 'id', 'donation_request_id');
    }
}
