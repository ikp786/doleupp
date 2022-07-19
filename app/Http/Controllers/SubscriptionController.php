<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plans;
use App\Models\User;
use Exception;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $plans = Plans::get();
        return view('public.subscriptions', compact('plans'));
    }

    public function orderPost(Request $request)
    {
            $user = User::find(auth()->user()->id);
            $input = $request->all();
            $token = $input['stripeToken'];

            try {
                $user->subscription($input['plane'])->create($token,[
                        'email' => $user->email,
                        'name' => $user->name,
                        'address' => [
                            'line1' => '510 Townsend St',
                            'postal_code' => '98140',
                            'city' => 'San Francisco',
                            'state' => 'CA',
                            'country' => 'US',
                        ],
                    ]);
                return back()->with('success','Subscription is completed.');
            } catch (Exception $e) {
                return back()->with('success',$e->getMessage());
            }
    }
}
