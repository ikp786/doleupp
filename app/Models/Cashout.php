<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use DB;

class Cashout extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $casts = [
        'created_at'  => 'datetime:m/d/Y H:i A',
        'updated_at' => 'datetime:m/d/Y H:i A',
    ];

    protected $appends = ['title', 'amount'];

    public function getTitleAttribute()
    {
        return 'The transferee accepts.';
    }

    public function getAmountAttribute()
    {
        $amount = str_replace(setting('currency_symbol'), '', "{$this->redeemed_amount}")+str_replace(setting('currency_symbol'), '', "{$this->cash_out_commission}")+str_replace(setting('currency_symbol'), '', "{$this->fee_amount}");
        return setting('currency_symbol').number_format($amount,2,'.','');
    }

    public function getRedeemedAmountAttribute($value) {
        return setting('currency_symbol').number_format($value,2,'.','');
    }

    public function getCashOutCommissionAttribute($value) {
        return setting('currency_symbol').number_format($value,2,'.','');
    }

    public function getFeeAmountAttribute($value) {
        return setting('currency_symbol').number_format($value,2,'.','');
    }

    public function getFeeForDonationAttribute($value) {
        return setting('currency_symbol').number_format($value,2,'.','');
    }

    public function donation_request()
    {
        return $this->hasOne(DonationRequest::class, 'id', 'donation_request_id')
            ->withCount(['views', 'comments', 'shares', 'donation_for_redeem'])
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
            }])
            ->withCount(['donors as donation_earned' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'earned');
            }])
            ->withCount(['donors as donation_redeemed' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'redeemed');
            }])->withCount(['rating' => function($query) {
                $query->select(DB::raw('COALESCE(avg(rating),0)'));
            }])->with(['user', 'category', 'donation_for_redeem']);
    }
}
