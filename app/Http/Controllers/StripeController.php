<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Cashout;
use App\Models\Donation;
use App\Models\LazorDonation;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Wishlist as WishlistModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Matrix\Operators\Subtraction;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use Session;
use Stripe;
use Redirect;

class StripeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paymentFrom = $request->paymentFrom ?? 'web';
        $subscriptionId = $request->id;
        $donations='';
        if (!empty($subscriptionId)) {
            $subecription = Subscription::find($subscriptionId);
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $subecription->price . "00",
                'currency' => 'INR',
                'payment_method_types' => [
                    'card'
                ],
            ]);

            $subecription = Subscription::find($subscriptionId);
            $subecription->payment_id = $intent->id;
            $subecription->paymentgateway = "stripe";
            $subecription->save();
        } else {
            $intent = '';
        }
        $type='subscription';
        return view("stripe", compact("intent", "paymentFrom", "type","donations"));
    }

    public function stripePost(Request $request)
    {
        $paymentIntent = $request->paymentIntent;

        $checksubscription = Subscription::where("payment_id", $paymentIntent['id'])->first();
        if (!empty($checksubscription)) {
            $sub = Subscription::find($checksubscription->id);
            if ($paymentIntent['status'] == "succeeded") {
                $sub->status = "Success";
                User::where('id', $sub->user_id)->update(['role' => 'recipient', 'subscription_ends_at' => $sub->ends_at]);
            } else {
                $sub->status = "Failed";

            }
            $sub->paymentgateway = "stripe";
            $sub->data = json_encode($paymentIntent);
            $sub->save();

        }
        if ($paymentIntent['status'] == "succeeded") {
            echo 1;
        } else {
            echo 2;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function googlepay(Request $request)
    {
        $subscriptionId = $request->id;
        $subecription = Subscription::find($subscriptionId);
        $amount = $subecription->price;
        $donations='';
        $type='subscription';
        return view("googlepay", compact("type","subecription","amount", "donations"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function gpayresponse(Request $request)
    {
        $paymentData = $request->paymentData;
        $status = $request->status;
        $orderId = $request->orderId;

        $checksubscription = Subscription::where("id", $orderId)->first();
        if (!empty($checksubscription)) {
            $sub = Subscription::find($checksubscription->id);
            if ($status == "Success") {
                $sub->status = "Success";
                User::where('id', $sub->user_id)->update(['role' => 'recipient', 'subscription_ends_at' => $sub->ends_at]);
            } else {
                $sub->status = "Failed";
            }
            $sub->paymentgateway = "googlepay";
            $sub->data = json_encode($paymentData);
            $sub->save();

        }
        if ($status == "Success") {
            echo 1;
        } else {
            echo 2;
        }
    }

    public function donation(Request $request)
    {
        $subscriptionId = $request->id;
        $paymentId = $request->paymentId;
        $type = $request->type ?? 'donation';
        $donations='';
        if (!empty($subscriptionId)) {
            //$subecription = Donation::find($subscriptionId);
            $d_pay = Donation::where('payment_id', $paymentId)->get();
            if (!$d_pay || count($d_pay) < 1) {
                $cd_pay = LazorDonation::where('payment_id', $paymentId)->first();
                if($cd_pay) {
                    $amount = 0;
                    $donations = '';
                    $amount = round($cd_pay->amount, 2)*100;
                    Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                    $intent = \Stripe\PaymentIntent::create([
                        'amount' => $amount,
                        'currency' => 'INR',
                        'payment_method_types' => [
                            'card'
                        ],
                    ]);

                    LazorDonation::where('payment_id', $paymentId)->update(['payment_id' => $intent->id]);
                } else {
                    return redirect()->to(url('api/subscription/payment/status?success=0&type='.$type.'&donations='.$donations));
                }
            } else {
                $d_amount = 0;
                $donations=[];
                foreach($d_pay as $item) {
                    $donations[]=$item->donation_request_id;
                    $amt = ($item->amount+$item->admin_commission)-$item->amount_from_wallet;
                    $d_amount += $amt;
                }
                $amount = round($d_amount, 2)*100;
                $donations=implode(',',$donations);
                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $intent = \Stripe\PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => 'INR',
                    'payment_method_types' => [
                        'card'
                    ],
                ]);

                Donation::where('payment_id', $paymentId)->update(['payment_status' => 'stripe', 'payment_id' => $intent->id]);
                /*$subecription = Donation::find($subscriptionId);
                $subecription->payment_id = $intent->id;
                $subecription->payment_status = "stripe";
                $subecription->save();*/
            }
        } else {
            $donations='';
            $d_pay = Donation::where('payment_id', $request->payment_intent)->get();
            if($d_pay->count() > 0) {
                $donations=[];
                foreach($d_pay as $item) {
                    $donations[] = $item->donation_request_id;
                }
                $donations=implode(',',$donations);
            } else {
                $find =LazorDonation::where('payment_id', $request->payment_intent)->first();
                if($find) {
                    $type='corporation';
                }
            }
            $intent = '';
        }
        return view("stripedonation", compact("intent", "donations", "type"));
    }

    public function striperesponsedonation(Request $request)
    {
        $paymentIntent = $request->paymentIntent;

        /*$checksubscription = Donation::where("payment_id", $paymentIntent['id'])->first();
        if (!empty($checksubscription)) {
            $sub = Donation::find($checksubscription->id);
            if ($paymentIntent['status'] == "succeeded") {
                $sub->status = "earned";
            } else {
                $sub->status = "pending";
            }
            $sub->data = json_encode($paymentIntent);
            $sub->save();
        }*/
        $user_id = 0;
        if ($paymentIntent['status'] == "succeeded") {
            $d_pay = Donation::where('payment_id', $paymentIntent['id'])->get();
            $donations = '';
            $d_amount = 0;
            $cd_pay=LazorDonation::where('payment_id', $paymentIntent['id'])->first();
            if($d_pay->count() > 0) {
                $donations = [];
                foreach ($d_pay as $k => $item) {
                    $user_id = $item->donation_by;
                    $donations[$k] = $item->donation_request_id;
                    $d_amount += $item->amount_from_wallet;
                    Donation::where('id', $item->id)->update(['status' => 'earned']);
                    WishlistModel::where('item_id', $item->donation_request_id)->where('user_id', $user_id)->delete();
                }
                $cashouts = Cashout::with('donation_request')->whereHas('donation_request', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                })->whereDate('created_at', '>', Carbon::now()->subDays(7))->get();
                foreach ($cashouts as $cashout) {
                    if ($d_amount > 0) {
                        if ($d_amount >= $cashout->fee_for_donation) {
                            $fee_for_donation = 0;
                            $d_amount = $d_amount - $cashout->fee_for_donation;
                        } else {
                            $fee_for_donation = $cashout->fee_for_donation - $d_amount;
                            $d_amount = 0;
                        }
                        Cashout::where('id', $cashout->id)->update(['fee_for_donation' => $fee_for_donation]);
                    } else {
                        break;
                    }
                }
                echo 1;
            } elseif($cd_pay) {
                LazorDonation::where('payment_id', $paymentIntent['id'])->update(['status' => 'success']);
                echo 1;
            } else {
                echo 2;
            }
        } else {
            echo 2;
        }
    }

    public function gpayresponsedonation(Request $request)
    {
        $paymentData = $request->paymentData;
        $status = $request->status;
        $orderId = $request->orderId;

        $user_id = 0;
        if ($status == "Success") {
            $payment_id = $request->orderId;
            $cp_pay=LazorDonation::where('payment_id', $payment_id)->first();
            if($cp_pay){
                LazorDonation::where('id', $cp_pay->id)->update([
                    'status' => 'success'
                ]);
                echo 1;
            } else {
                $d_pay = Donation::where('payment_id', $orderId)->get();
                $donations = [];
                $d_amount = 0;
                foreach ($d_pay as $k => $item) {
                    $user_id = $item->donation_by;
                    $donations[$k] = $item->donation_request_id;
                    $d_amount += $item->amount_from_wallet;
                    Donation::where('id', $item->id)->update(['status' => 'earned']);
                    WishlistModel::where('item_id', $item->donation_request_id)->where('user_id', $user_id)->delete();
                }
                $cashouts = Cashout::with('donation_request')->whereHas('donation_request', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                })->whereDate('created_at', '>', Carbon::now()->subDays(7))->get();
                foreach ($cashouts as $cashout) {
                    if ($d_amount > 0) {
                        if ($d_amount >= $cashout->fee_for_donation) {
                            $fee_for_donation = 0;
                            $d_amount = $d_amount - $cashout->fee_for_donation;
                        } else {
                            $fee_for_donation = $cashout->fee_for_donation - $d_amount;
                            $d_amount = 0;
                        }
                        Cashout::where('id', $cashout->id)->update(['fee_for_donation' => $fee_for_donation]);
                    } else {
                        break;
                    }
                }
            }
            echo 1;
        } else {
            echo 2;
        }

        /*$checksubscription = Donation::where("id", $orderId)->first();
        if (!empty($checksubscription)) {
            $sub = Donation::find($checksubscription->id);
            if ($status == "Success") {
                $sub->status = "earned";
            } else {
                $sub->status = "Pending";
            }
            $sub->paymentgateway = "googlepay";

            $sub->data = json_encode($paymentData);
            $sub->save();

        }
        if ($status == "Success") {
            echo 1;
        } else {
            echo 2;
        }*/
    }

    public function googlepaydonation(Request $request)
    {
        /*$subscriptionId = $request->id;
        $subecription = Donation::find($subscriptionId);
        return view("googlepay", compact("subecription"));*/
        $subscriptionId = $request->id;
        $paymentId = $request->paymentId;
        $type=$request->type ?? 'donation';
        $donations = '';
        if (!empty($subscriptionId)) {
            $d_pay = Donation::where('payment_id', $paymentId)->get();
            if (!$d_pay || count($d_pay) < 1) {
                $payment_id = $request->paymentId;
                $cp_pay=LazorDonation::where('payment_id', $payment_id)->first();
                $subecription=$cp_pay;
                if($cp_pay){
                    $donations=$cp_pay;
                    $amount=$cp_pay->amount;
                    $type='corporation';
                    /*LazorDonation::where('id', $cp_pay->id)->update([
                        'status' => 'success'
                    ]);
                    return Redirect::route('subscription.payment-status', ['success' => '1', 'type' => $type, 'donations' => '']);*/
                    return view("googlepay", compact("type","subecription","amount","donations", "type"));
                }
                return redirect()->to(url('api/subscription/payment/status?success=0&type='.$type.'&donations='.$donations));
            } else {
                $subecription = Donation::find($subscriptionId);
                $d_amount = 0;
                $donations = [];
                foreach($d_pay as $item) {
                    $donations[]=$item->donation_request_id;
                    $amt = ($item->amount+$item->admin_commission)-$item->amount_from_wallet;
                    $d_amount += $amt;
                }
                $donations=implode(",", $donations);
                $amount = round($d_amount, 2);
                return view("googlepay", compact("type","subecription","amount","donations"));
            }
        } else {
            return redirect()->to(url('api/subscription/payment/status?success=0&type='.$type.'&donations='.$donations));
        }
        return redirect()->to(url('api/subscription/payment/status?success=0&type='.$type.'&donations='.$donations));
    }

}
