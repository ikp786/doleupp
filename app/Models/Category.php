<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use DB;

class Category extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $casts = [
        'created_at'  => 'datetime:m/d/Y H:i A',
        'updated_at' => 'datetime:m/d/Y H:i A',
    ];

    public function donation_requests()
    {
        return $this->hasMany(DonationRequest::class, 'category_id', 'id')->withCount(['views', 'comments', 'shares'])
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
            }])->withCount(['rating' => function($query) {
                $query->select(DB::raw('COALESCE(avg(rating),0)'));
            }])->with('user', 'category');
    }

    public function fundraisers()
    {
        return $this->hasMany(DonationRequest::class, 'category_id', 'id')->withCount(['views', 'comments', 'shares'])
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
            }])->withCount(['rating' => function($query) {
                $query->select(DB::raw('COALESCE(avg(rating),0)'));
            }])->with('user', 'category')->having('donation_received', '<', \DB::raw('donation_amount'))
            ->where('status', 'Approved')->latest()->orderByDesc('is_prime');//->limit(3);
    }
}
