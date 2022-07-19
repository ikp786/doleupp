<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $hidden = [
        'onboarding_text',
        'onboarding_text_2',
        'onboarding_text_3'
    ];

    public $timestamps = false;

    /*public function getSubscriptionPriceAttribute($value) {
        return number_format($value,2,'.','');
    }

    public function getDonationPriceAttribute($value) {
        return number_format($value,2,'.','');
    }

    public function getPrimeDonationPriceAttribute($value) {
        return number_format($value,2,'.','');
    }*/
}
