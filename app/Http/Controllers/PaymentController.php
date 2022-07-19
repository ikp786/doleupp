<?php

namespace App\Http\Controllers;

use App\Models\Plans;
use Illuminate\Http\Request;
use Exception;

class PaymentController extends Controller
{
    public function index() {
        $data = [
            'intent' => auth()->user()->createSetupIntent()
        ];

        return view('public.payment')->with($data);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $plan = Plans::where('identifier', $request->plan)->first();
        if($plan) {
            try {
                $request->user()->newSubscription('default', $plan->stripe_id)->create($request->token);
                return back();
            } catch (Exception $e) {
                dd($e);
            }
        } else {
            echo 'failed';
        }
    }
}
