<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class DonationRequest extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $casts = [
        'created_at'  => 'datetime:m/d/Y H:i A',
        'updated_at' => 'datetime:m/d/Y H:i A',
    ];

    /*public function getDonationAmountAttribute($value) {
        return number_format($value,2,'.','');
    }

    public function getDonationReceivedAttribute($value) {
        return number_format($value,2,'.','');
    }

    public function getDonationEarnedAttribute($value) {
        return number_format($value,2,'.','');
    }

    public function getDonationRedeemedAttribute($value) {
        return number_format($value,2,'.','');
    }*/

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id')->withDefault(function($data) {
            $values = \Schema::getColumnListing((new Category())->getTable());
            foreach ($values as $key => $value) {
                $data[$value] = null;
            }
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'donation_request_id', 'id');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'donation_request_id', 'id')->whereNull('parent_id')->with('comment_user', 'comment_tag');
    }

    public function views()
    {
        return $this->hasMany(View::class, 'donation_request_id', 'id');//->groupBy('user_id');
    }

    public function shares()
    {
        return $this->hasMany(Share::class, 'donation_request_id', 'id');
    }

    public function wishlist()
    {
        return $this->hasOne(Wishlist::class, 'item_id', 'id')->where('user_id', auth()->user()->id ?? 0);
    }

    public function donors()
    {
        return $this->hasMany(Donation::class, 'donation_request_id', 'id');
    }

    public function real_donors()
    {
        return $this->hasMany(Donation::class, 'donation_request_id', 'id')->whereIn('status', ['earned', 'redeemed']);
    }

    public function latest_donors()
    {
        return $this->hasMany(Donation::class, 'donation_request_id', 'id')->whereIn('status', ['earned', 'redeemed'])->limit(3);
    }

    public function donation_for_redeem()
    {
        return $this->hasMany(Donation::class, 'donation_request_id', 'id')->where('status', 'earned');
    }

    public function rating()
    {
        return $this->hasMany(Rating::class, 'donation_request_id', 'id');//->avg('rating');
    }

    public function rating_by_me()
    {
        return $this->hasOne(Rating::class, 'donation_request_id', 'id')->where('user_id', auth()->user()->id ?? 0);
    }

    public function reports()
    {
        return $this->hasMany(DonationRequestReport::class, 'donation_request_id', 'id');
    }

    public function is_reported()
    {
        return $this->hasOne(DonationRequestReport::class, 'donation_request_id', 'id')->where('user_id', auth()->user()->id ?? 0);
    }
}
