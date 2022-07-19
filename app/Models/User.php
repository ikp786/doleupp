<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable, Loggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    // protected $fillable = [
    //     'country_code',
    //     'phone',
    //     'phone_verified_at',
    // ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
        'facebook_id',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'phone_verified_at',
        'email_verified_at',
        'is_admin',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dob' => 'datetime:m/d/Y',
        //'subscription_ends_at' => 'datetime:m/d/Y H:i A',
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime:m/d/Y H:i A',
        'live_at'  => 'datetime:m/d/Y H:i A',
        'created_at'  => 'datetime:m/d/Y H:i A',
        'updated_at' => 'datetime:m/d/Y H:i A',
    ];

    public function donation_received()
    {
        return $this->hasMany(Donation::class, 'donation_to', 'id');
    }

    public function donation_send()
    {
        return $this->hasMany(Donation::class, 'donation_by', 'id');
    }

    public function bank_details()
    {
        return $this->hasOne(BankDetail::class, 'user_id', 'id');
    }

    public function card_details()
    {
        return $this->hasOne(CardDetail::class, 'user_id', 'id');
    }

    public function security_questions()
    {
        return $this->hasMany(UserSecurityQuestion::class, 'user_id', 'id');
    }
}
