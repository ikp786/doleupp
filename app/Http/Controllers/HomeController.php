<?php

namespace App\Http\Controllers;

use App\Models\Cashout;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\DonationRequest;
use App\Models\Faq;
use App\Models\Setting;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $reels = DonationRequest::where('user_id', auth()->user()->id)->withCount(['views', 'comments', 'shares', 'wishlist', 'donation_for_redeem'])
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
            }])
            ->withCount(['donors as donation_earned' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'earned');
            }])
            ->withCount(['donors as donation_redeemed' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'redeemed');
            }])->with(['user', 'category', 'donation_for_redeem'])
            ->latest()->get();
        $donations = Donation::with('donation_to', 'donation_request.user', 'donation_request.category')
            ->where('donation_by', auth()->user()->id)->latest()->get();
        $cashouts = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'donation_for_redeem'])->where('status', 'Approved')
//            ->whereHas('donation_for_redeem', function ($q){
//                $q->where('', );
//            })
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
            }])
            ->withCount(['donors as donation_earned' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'earned');
            }])
            ->withCount(['donors as donation_redeemed' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'redeemed');
            }])->with(['user', 'category', 'donation_for_redeem'])
            ->has('donation_for_redeem', '>', 0)
            ->where('user_id', auth()->user()->id)->latest()->get();
        $wishlists = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist'])->where('status', 'Approved')
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
            }])->with(['user', 'category', 'wishlist'])->whereHas('wishlist', function($query){
                $query->where('user_id', auth()->user()->id);
            })->get();
        $settings = Setting::where('id', 1)->first();
        $faqs = Faq::get();
        $amount_for_donate = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
            $q->where('user_id', auth()->user()->id);
        })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
        /*$reels = DonationRequest::where('user_id', auth()->user()->id)->latest()->get();
        $donations = Donation::with('donation_to', 'donation_request.user', 'donation_request.category')
        ->where('donation_by', auth()->user()->id)->latest()->limit(10)->get();
        $cashouts = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'donation_for_redeem'])
//            ->whereHas('donation_for_redeem', function ($q){
//                $q->where('', );
//            })
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'));
            }])
            ->withCount(['donors as donation_earned' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'earned');
            }])
            ->withCount(['donors as donation_redeemed' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'redeemed');
            }])->with(['user', 'category', 'donation_for_redeem'])->where('user_id', auth()->user()->id)->latest()->get();
        $wishlists = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist'])
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'));
            }])->with(['user', 'category', 'wishlist'])->whereHas('wishlist', function($query){
                $query->where('user_id', auth()->user()->id);
            })->get();*/
        $settings = Setting::where('id', 1)->first();
        $faqs = Faq::get();
        return view('public.my-account', compact('reels', 'donations', 'cashouts', 'wishlists', 'settings', 'faqs', 'amount_for_donate'));
    }
}
