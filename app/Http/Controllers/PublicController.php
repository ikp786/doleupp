<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Bank;
use App\Models\Faq;
use App\Models\Feedback;
use App\Models\Rating;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Share;
use App\Models\View;
use App\Models\Wishlist;
use App\Models\Wishlist as WishlistModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\BankDetail;
use App\Models\CardDetail;
use App\Models\Cashout;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Donation;
use App\Models\DonationPayment;
use App\Models\DonationRequest;
use App\Models\LazorDonation;
use App\Models\News;
use App\Models\Reason;
use App\Models\Referral;
use App\Models\SecurityQuestion;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSecurityQuestion;
use App\Models\Notification;
use Carbon\Carbon;
use Validator;
use FFMpeg;
use DB;
use URL;
use Session;
use Redirect;
use Input;
use Str;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Api\Payout;
use PayPal\Api\PayoutSenderBatchHeader;
use PayPal\Api\PayoutItem;
use PayPal\Api\Currency;

// Used to process plans
use PayPal\Api\ChargeModel;
//use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
//use PayPal\Rest\ApiContext;
//use PayPal\Auth\OAuthTokenCredential;

// use to process billing agreements
use PayPal\Api\Agreement;
//use PayPal\Api\Payer;
//use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;
use PayPal\Api\AgreementStateDescriptor;

class PublicController extends Controller
{
    private $_api_context;

//    private $apiContext;
//    private $mode;
//    private $client_id;
//    private $secret;
    private $plan_id;

    public function __construct()
    {
        $paypal_configuration = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_configuration['client_id'], $paypal_configuration['secret']));
        $this->_api_context->setConfig($paypal_configuration['settings']);
        $this->plan_id = 'P-1B466965G8858912MXGNLAUY';
    }

    // public function signin()
    // {
    //     return view('public.login');
    // }

    // public function signup()
    // {
    //     return view('public.signup');
    // }

    public function gifs($search = 'tranding', Request $request)
    {
        $offset = 0;
        $limit=25;
        if ($request->get('page') > 1) {
            $offset = (($request->get('page')-1)*$limit);
        }
        $search2 = 'tranding';
        if($request->get('search') != '' && $request->get('search') != ' ') {
            $search2 = $request->get('search');
        }
        $response = Http::get('https://api.giphy.com/v1/gifs/search?api_key='.env('GIPHY_API_KEY').'&offset='.$offset.'&limit='.$limit.'&q='.$search2.'&bundle=low_bandwidth');
        $data = '';
        $arr = json_decode($response->getBody(), true);
        $gifs = $arr['data'];
        foreach($gifs as $key => $gif) {
            // echo json_encode($gif);die;
            $data.= '<div class="col-md-3 mt-2">
                <img src="'.$gif['images']['downsized']['url'].'" class="form-img" width="100%" height="70px"/>
            </div>';
        }
        return $data;
    }

    public function password_change()
    {
        return view('public.password-change');
    }

    public function index()
    {
        if(auth()->user()) {
            $top_reels = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist'])
                ->withCount(['donors as donation_received' => function ($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                }])
                ->withCount(['rating' => function ($query) {
                    $query->select(DB::raw('COALESCE(avg(rating),0)'));
                }])->with('user', 'category')->having('donation_received', '<', \DB::raw('donation_amount'))
                ->where('status', 'Approved')->latest()->orderByDesc('is_prime')->limit(6)->get();
        } else {

            $top_reels = DonationRequest::withCount(['views', 'comments', 'shares'])
                ->withCount(['donors as donation_received' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                }])
                ->withCount(['rating' => function($query) {
                    $query->select(DB::raw('COALESCE(avg(rating),0)'));
                }])->with('user', 'category')->having('donation_received', '<', \DB::raw('donation_amount'))
                ->where('status', 'Approved')->latest()->orderByDesc('is_prime')->limit(6)->get();
        }
        return view('public.index', compact('top_reels'));
    }

    public function how_it_works()
    {
        $settings = Setting::find(1);
        return view('public.how-it-works', compact('settings'));
    }

    public function fundraisers()
    {
        $top_reels = DonationRequest::withCount(['views', 'comments', 'shares'])
        ->withCount(['donors as donation_received' => function($query) {
            $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
        }])->withCount(['rating' => function($query) {
                $query->select(DB::raw('COALESCE(avg(rating),0)'));
        }])->with('user', 'category')->having('donation_received', '<', \DB::raw('donation_amount'))
        ->where('status', 'Approved')->having('donation_received', '<', \DB::raw('donation_amount'))
        ->latest()->orderByDesc('is_prime')->limit(6)->get();
        $donation_categories = Category::get();
        $donation_by_category = Category::with('fundraisers.user')->withCount('fundraisers')
            ->having('fundraisers_count', '>', 0)->limit(10)->get()->map(function($donation) {
            $donation->setRelation('fundraisers', $donation->fundraisers->take(3));
            return $donation;
        });
        /*Album::with('images')->get()->map(function($album) {
            $album->setRelation('images', $album->images->take(3));
            return $album;
        });*/
        //echo json_encode($donation_by_category);die();
        return view('public.fundraisers', compact('top_reels', 'donation_categories', 'donation_by_category'));
    }

    public function fundraisers_details($slug, Request $request)
    {
        $details = Category::where('slug', $slug)->first();
        if($details) {
            $reels = DonationRequest::withCount(['views', 'comments', 'shares'])
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
            }])->withCount(['rating' => function($query) {
                    $query->select(DB::raw('COALESCE(avg(rating),0)'));
            }])->with('user', 'category')->having('donation_received', '<', \DB::raw('donation_amount'))
            ->where('category_id', $details->id)->where('status', 'Approved')->latest()->orderByDesc('is_prime')->paginate(6);
            if ($request->ajax()) {
                $view = view('public.reels', compact('reels'))->render();
                return response()->json(['html'=>$view]);
            }

            return view('public.fundraisers_details', compact('details', 'reels'));
        }
        abort(404);
    }

    public function help_center()
    {
        $faqs = Faq::get();
        return view('public.help-center', compact('faqs'));
    }

    public function faq()
    {
        $faqs = Faq::get();
        return view('public.faq', compact('faqs'));
    }

    public function doleupp_tips()
    {
        $faqs = Faq::get();
        return view('public.doleupp-tips', compact('faqs'));
    }

    public function contact()
    {
        $reasons = Reason::where('status', 'Active')->where('reason_for', 'contact')->orderByDesc('id')->get();
        return view('public.contact', compact('reasons'));
    }

    public function contactCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'company_name' => 'required|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'phone' => 'required|numeric|regex:/^([0-9\s\-\+\(\)]*)$/|digits_between:6,15',
            'email' => 'required|email',
            'reason_id' => 'required|exists:reasons,id',
            'message' => 'required|min:10|banned_words'
        ]);

        $input = $request->only('name', 'company_name', 'phone', 'email', 'reason_id', 'message');
        $insert = Contact::create($input);
        if($insert) {
            return redirect()->back()->withSuccess('Your contact form submitted successfully, We will contact you soon.');
        }
        return redirect()->back()->withError('Your contact form submitting failed, Please try again.');
    }

    public function privacy_policy()
    {
        return view('public.privacy-policy');
    }

    public function terms_and_conditions()
    {
        return view('public.terms-and-conditions');
    }

    public function personal_information()
    {
        return view('public.personal-information');
    }

    public function personalinformation(Request $request)
    {
        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });

        $request->validate([
            'name' => 'required|min:3|max:255|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            // 'username' => 'required|without_spaces|max:16|unique:users,username,'.auth()->user()->id.',id|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'password' => 'required|min:6',
            // 'university' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            // 'occupation' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'address' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'state' => 'required|min:2|max:15|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'country' => 'required|min:2|max:15|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'email' => 'required|email|unique:users,email,'.auth()->user()->id.',id',
            'dob' => 'required|date_format:m/d/Y|before_or_equal:'.Carbon::now()->subYears(4)->format('m/d/Y'),
            'about' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'image' => 'nullable|mimes:jpg,png,jpeg',
            'referral_code' => 'nullable|exists:users,referral_code',
        ],[
            'username.without_spaces' => 'The username white space not allowed.'
        ]);

        $input = $request->only('name', 'username', 'password', 'university', 'occupation', 'address', 'state', 'country', 'email', 'dob', 'about');
        $input['dob']=Carbon::parse($input['dob'])->format('Y-m-d');
        $input['password'] = bcrypt($input['password']);
        $input['referral_code'] = ApiHelper::toRef(auth()->user()->id);

        if($request->image) {
            $imageName = $request->image->store('images/profile');
            $input['image'] = asset('storage/' . $imageName);

            $verify = ApiHelper::imageVerification($input['image']);
            if ($verify != 'success') {
                return redirect()->back()->withError('Image reject to upload because of '.$verify.', Please use another one.');
            }
        }
        $input['username']=$input['email'];
        $update = User::where('id', auth()->user()->id)->update($input);
        if ($update) {
            if($request->referral_code) {
                $referral_by = User::where('referral_code', $request->referral_code)->first();
                $id = [
                    'referral_by' => $referral_by->id,
                    'referral_to' => auth()->user()->id,
                ];
                $input2 = [
                    'referral_by' => $referral_by->id,
                    'referral_to' => auth()->user()->id,
                    'status' => 'Pending'
                ];
                Referral::updateOrCreate($id, $input2);
            }
            User::where('id', auth()->user()->id)->where('screen', 1)->update(['screen' => 2]);
            return redirect(route('security-questions'))->withSuccess('Personal information updated successfully.');
        }
        return redirect()->back()->withInput()->withError('Personal information updation failed, Please try again.');
    }

    public function security_questions()
    {
        $questions = SecurityQuestion::get();
        return view('public.security-questions', compact('questions'));
    }

    public function securityquestions(Request $request)
    {
        $request->validate([
            'security_questions' => 'required|array|min:3',
            'security_questions.*.question_id' => 'required|exists:security_questions,id',
            'security_questions.*.answer' => 'nullable|string|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
        ],['security_questions.*.answer.min' => 'The answer must be at least 3 characters.']);

        $security_questions = $request->only('security_questions');
        //echo json_encode($request->all());die;

        $answers = 0;
        foreach($security_questions['security_questions'] as $key => $item) {
            if (isset($item['answer']))
                $answers++;//return true;
        }

        if($answers >= 3) {
            foreach ($security_questions['security_questions'] as $key => $sq) {
                $id = [
                    'user_id' => auth()->user()->id,
                    'question_id' => $sq['question_id'],
                ];
                $input = [
                    'user_id' => auth()->user()->id,
                    'question_id' => $sq['question_id'],
                    'answer' => $sq['answer']
                ];
                $update = UserSecurityQuestion::updateOrCreate($id, $input);
            }
            if ($update) {
                User::where('id', auth()->user()->id)->where('screen', 2)->update(['screen' => 3]);
                return redirect(route('add-card'))->withSuccess('Security question updated successfully.');
            }
            return redirect()->back()->withInput()->withError('Security questions not update, Please try again.');
        }
        return redirect()->back()->withInput()->withError('Give at least three answers.');
    }

    public function banking_information()
    {
        $banks = Bank::all();
        return view('public.banking-information', compact('banks'));
    }

    public function bankinginformation(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|min:3|max:255|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'routing_number' => 'required',
            'account_number' => 'required|numeric|digits_between:9,18'
        ]);

        $input = $request->only('user_id', 'bank_name', 'routing_number', 'account_number');
        $id = [
            'user_id' => auth()->user()->id
        ];
        $insert = BankDetail::updateOrCreate($id, $input);
        if ($insert) {
            User::where('id', auth()->user()->id)->where('screen', 5)->update(['screen' => 6]);
            return redirect(route('subscription'))->withSuccess('Banking information updated successfully.');
        }
        return redirect()->back()->withError('Banking information updation failed, Please try again.');
    }

    public function add_card()
    {
        return view('public.add-card');
    }

    public function addcard(Request $request)
    {
        $request->validate([
            'card_number' => 'required',
            'expiry_date' => 'required',
            'cvv' => 'required|numeric|digits_between:3,6'
        ]);
        $input = $request->only('card_number', 'expiry_date', 'cvv');
        $id = [
            'user_id' => auth()->user()->id
        ];
        $insert = CardDetail::updateOrCreate($id, $input);
        if ($insert) {
            User::where('id', auth()->user()->id)->where('screen', 3)->update(['screen' => 4]);
            return redirect(route('i-am'))->withSuccess('Card added successfully.');
        }
        return redirect()->back()->withError('Card addition failed, Please try again.');
    }

    public function i_am()
    {
        $roles = Role::get();
        return view('public.i-am', compact('roles'));
    }

    public function iam(Request $request)
    {
        $request->validate([
            'role' => 'required|in:recipient,donor,unsure,both,corporate'
        ]);

        $input = $request->only('role');
        $update = User::where('id', auth()->user()->id)->update($input);
        if ($update) {
            User::where('id', auth()->user()->id)->where('screen', 4)->update(['screen' => 5]);
            /*if($request->role == 'donor') {
                return redirect(route('home'))->withSuccess("Welcome to DoleUpp.");
            }*/
            return redirect(route('banking-information'))->withSuccess("I'm updated successfully.");
        }
        return redirect()->back()->withError("I'm updation failed, Please try again.");
    }

    public function subscription()
    {
        return view('public.subscription');
    }

    public function subscription_pay()
    {
        $settings = Setting::find(1);
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName('Subscription Fee')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($settings->subscription_price);

        $item_list = new ItemList();
        $item_list->setItems(array($item));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($settings->subscription_price);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Pay for Yearly Subscription');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('subscription.paymentstatus'))
            ->setCancelUrl(URL::route('subscription.paymentstatus'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                return Redirect::route('subscription')->withError("Connection Timeout.");
            } else {
                return Redirect::route('subscription')->withError('Some error occur, sorry for inconvenient');
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return Redirect::route('subscription')->withError("Something went wrong, Please try after some time.");
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        Session::put('paypal_payment_id', $payment->getId());

        if(isset($redirect_url)) {
            if(auth()->user()->subscription_ends_at == NULL || auth()->user()->subscription_ends_at < Carbon::now()){
                $starts_from = Carbon::now();
                $ends_at = Carbon::now()->addYear();
            } else {
                $date = Carbon::parse(auth()->user()->subscription_ends_at);
                $now = Carbon::now();
                $diff = $date->diffInDays($now);

                $starts_from = Carbon::now()->addDays($diff);
                $ends_at = Carbon::now()->addYear()->addDays($diff);
            }
            $insert= Subscription::create([
                'name' => 'Yearly Subscription',
                'user_id' => auth()->user()->id,
                'payment_id' => $payment->getId(),
                'price' => $settings->subscription_price,
                'quantity' => 1,
                'starts_from' => $starts_from,
                'ends_at' => $ends_at,
                'status' => 'Pending'
            ]);
            Session::put('paypalLink',$redirect_url);
            return redirect("checkout/?orderId=".$insert->id."&type=subscription&paymentId=".$insert->payment_id);
            // return Redirect::away($redirect_url);
        }

        return Redirect::route('subscription')->withError('Unknown error occurred');
    }

    public function subscriptionPaymentStatus(Request $request)
    {
        $payment_id = $request->paymentId;//Session::get('paypal_payment_id');
        $subscription = Subscription::where('payment_id', $payment_id)->first();
        // echo $request->paymentId;
        // print_r($subscription);die;
        Session::forget('paypal_payment_id');
        if (empty($request->input('PayerID')) || empty($request->input('token')) || !$subscription) {
            Subscription::where('payment_id', $payment_id)->update(['status' => 'Failed']);
            return Redirect::route('subscription')->withError('Payment failed, Please try again.');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
            Subscription::where('id', $subscription->id)->update(['status' => 'Success']);
            User::where('id', $subscription->user_id)->update(['role' => 'recipient', 'subscription_ends_at' => $subscription->ends_at]);
            return Redirect::route('home')->withSuccess('Payment success, Thanks for subscription now are you upload your video for DoleUpp.');
        }

        Subscription::where('id', $subscription->id)->update(['status' => 'Failed']);
		return Redirect::route('subscription')->withError('Payment failed, Please try again.');
    }

    public function subscription_renew()
    {
        return view('public.subscription-renew');
    }

    public function profile()
    {
        $user_id = auth()->user()->id;
        $data['donation_send'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_by', $user_id)->sum('amount');
        $data['donation_received'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_to', $user_id)->sum('amount');
        if($data['donation_send'] >= 500 && $data['donation_send'] < 5000) {
            $badge = '<img class="mx-2" width="25" src="'.asset('assets/img/badge/bronze.png').'" alt=""><span class="text-success">Bronze</span>';
        } elseif($data['donation_send'] >= 5000 && $data['donation_send'] < 50000) {
            $badge = '<img class="mx-2" width="25" src="'.asset('assets/img/badge/silver.png').'" alt=""><span class="text-success">Silver</span>';
        } elseif($data['donation_send'] >= 50000 && $data['donation_send'] < 100000) {
            $badge = '<img class="mx-2" width="25" src="'.asset('assets/img/badge/gold.png').'" alt=""><span class="text-success">Gold</span>';
        } elseif($data['donation_send'] >= 100000 && $data['donation_send'] < 1000000) {
            $badge = '<img class="mx-2" src="'.asset('assets/img/badge/platinum.png').'" alt=""><span class="text-success">Platinum</span>';
        } elseif($data['donation_send'] >= 1000000) {
            $badge = '<img class="mx-2" src="'.asset('assets/img/badge/diamond.png').'" alt=""><span class="text-success">Black Diamond</span>';
        } else {
            $badge = '<span class="text-danger">No Badge</span>';
        }
        $data['badge'] = $badge;
        $data['amount_for_donate'] = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
            $q->where('user_id', auth()->user()->id);
        })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
        $donation_to = Donation::where('donation_by', $user_id)->whereIn('status', ['earned', 'redeemed'])->latest()->get();
        $donation_from = Donation::where('donation_to', $user_id)->whereIn('status', ['earned', 'redeemed'])->latest()->get();
        $reels = DonationRequest::where('user_id', auth()->user()->id)->where('status', 'Approved')
            ->withCount(['views', 'comments', 'shares', 'wishlist', 'donation_for_redeem'])
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
            }])->with(['user', 'category', 'donation_for_redeem'])
            ->latest()->get();
        $shareProfile = \Share::page(
            route('donors', ['username' => auth()->user()->id]),
            auth()->user()->name,
        )
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->whatsapp()
            ->reddit();

        $invitationText = 'Hey, we are invite you for DoleUpp app registration, Complete your registration on Send/Received DoleUpps.';
        $shareInvitation = \Share::page(
            route('register', ['referral_code' => auth()->user()->referral_code]),
            $invitationText,
        )
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->whatsapp()
            ->reddit();
        return view('public.profile', compact('donation_to', 'donation_from', 'reels', 'data', 'shareProfile', 'shareInvitation', 'invitationText'));
    }

    public function profile_edit()
    {
        return view('public.profile-edit');
    }

    public function profileedit(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            // 'username' => 'required|without_spaces|max:16|unique:users,username',
            // 'password' => 'required|min:6',
            'university' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'occupation' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'address' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'state' => 'required|min:2|max:15|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'country' => 'required|min:2|max:15|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            // 'email' => 'required|email|unique:users,email',
            'dob' => 'required|date_format:m/d/Y|before_or_equal:'.Carbon::now()->subYears(4)->format('m/d/Y'),
            'about' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'image' => 'nullable|mimes:jpg,png,jpeg'
        ]);

        $input = $request->only('name', 'university', 'occupation', 'address', 'state', 'country', 'dob', 'about');
        $input['dob']=Carbon::parse($input['dob'])->format('Y-m-d');
        if($request->image) {
            $imageName = $request->image->store('images/profile');
            $input['image'] = asset('storage/' . $imageName);

            $verify = ApiHelper::imageVerification($input['image']);
            if ($verify != 'success') {
                return redirect()->back()->withError('Image reject to upload because of '.$verify.', Please use another one.');
            }
        }
        $update = User::where('id', auth()->user()->id)->update($input);
        if ($update) {
            User::where('id', auth()->user()->id)->where('screen', 1)->update(['screen' => 2]);
            return redirect()->back()->withSuccess('Profile updated successfully.');
        }
        return redirect()->back()->withError('Profile updation failed, Please try again.');
    }

    public function donorProfile($username)
    {
        $data = User::find($username);//where('username', $username)->first();
        if(!$data) {
            abort(404);
        }
        $user_id = $data->id;
        $data['donation_send'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_by', $user_id)->sum('amount');
        $data['donation_received'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_to', $user_id)->sum('amount');
        if($data['donation_send'] >= 500 && $data['donation_send'] < 5000) {
            $badge = '<img class="mx-2" width="25" src="'.asset('assets/img/badge/bronze.png').'" alt=""><span class="text-success">Bronze</span>';
        } elseif($data['donation_send'] >= 5000 && $data['donation_send'] < 50000) {
            $badge = '<img class="mx-2" width="25" src="'.asset('assets/img/badge/silver.png').'" alt=""><span class="text-success">Silver</span>';
        } elseif($data['donation_send'] >= 50000 && $data['donation_send'] < 100000) {
            $badge = '<img class="mx-2" width="25" src="'.asset('assets/img/badge/gold.png').'" alt=""><span class="text-success">Gold</span>';
        } elseif($data['donation_send'] >= 100000 && $data['donation_send'] < 1000000) {
            $badge = '<img class="mx-2" src="'.asset('assets/img/badge/platinum.png').'" alt=""><span class="text-success">Platinum</span>';
        } elseif($data['donation_send'] >= 1000000) {
            $badge = '<img class="mx-2" src="'.asset('assets/img/badge/diamond.png').'" alt=""><span class="text-success">Black Diamond</span>';
        } else {
            $badge = '<span class="text-danger">No Badge</span>';
        }
        $data['badge'] = $badge;
        $data['amount_for_donate'] = Cashout::with('donation_request')->whereHas('donation_request', function($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
        $donation_to = Donation::where('donation_by', $user_id)->whereIn('status', ['earned', 'redeemed'])->latest()->get();
        $donation_from = Donation::where('donation_to', $user_id)->whereIn('status', ['earned', 'redeemed'])->latest()->get();
        $reels = DonationRequest::where('user_id', $user_id)->where('status', 'Approved')
            ->withCount(['views', 'comments', 'shares', 'wishlist', 'donation_for_redeem'])
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
            }])->with(['user', 'category', 'donation_for_redeem'])
            ->latest()->get();
        $shareProfile = \Share::page(
            route('donors', ['username' => $data->id]),
            $data->name,
        )
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->whatsapp()
            ->reddit();
        return view('public.profile-other', compact('donation_to', 'donation_from', 'reels', 'data', 'shareProfile'));
    }

    public function bank_detail_edit()
    {
        $banks = Bank::all();
        $bank_detail = BankDetail::where('user_id', auth()->user()->id)->first();
        return view('public.bank-detail-edit', compact('bank_detail', 'banks'));
    }

    public function bankdetailedit(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|min:3|max:255|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'routing_number' => 'required',
            'account_number' => 'required|numeric|digits_between:9,18'
        ]);

        $input = $request->only('bank_name', 'routing_number', 'account_number');
        $id = [
            'user_id' => auth()->user()->id
        ];
        $insert = BankDetail::updateOrCreate($id, $input);
        if ($insert) {
            User::where('id', auth()->user()->id)->where('screen', 3)->update(['screen' => 4]);
            return redirect()->back()->withSuccess('Banking information updated successfully.');
        }
        return redirect()->back()->withError('Banking information updation failed, Please try again.');
    }

    public function my_account()
    {
        $categories = Category::get();
        return view('public.my-account', compact('categories'));
    }

    public function lazor_reels()
    {
        $reels = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'donation_for_redeem'])
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
            }])->with(['user', 'category', 'donation_for_redeem'])
            ->where('user_id', auth()->user()->id)->latest()->get();
        return view('public.lazor-reels', compact('reels'));
    }

    public function my_donations()
    {
        $donations = Donation::with('donation_to', 'donation_request.user', 'donation_request.category')
            ->where('donation_by', auth()->user()->id)->latest()->get();//->limit(10)->get();
        $lazor_donations = LazorDonation::where('user_id', auth()->user()->id)->latest()->get();
        return view('public.my-donations', compact('donations', 'lazor_donations'));
    }

    public function holding_area()
    {
        $wishlists = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist'])->where('status', 'Approved')
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
            }])->withCount(['rating' => function($query) {
                $query->select(DB::raw('COALESCE(avg(rating),0)'));
            }])->with(['user', 'category', 'wishlist'])->whereHas('wishlist', function($query){
                $query->where('user_id', auth()->user()->id);
            })->having('donation_received', '<', \DB::raw('donation_amount'))->get();
        $amount_for_donate = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
            $q->where('user_id', auth()->user()->id);
        })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
        $setting = Setting::find(1);
        $admin_commission = $setting->admin_commission;
        return view('public.my-holding-area', compact('wishlists', 'amount_for_donate', 'admin_commission'));
    }

    public function my_wallet()
    {
        $user_id = auth()->user()->id;
        $donation_send = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_by', $user_id)->sum('amount');
        $donation_received = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_to', $user_id)->sum('amount');
        $cashout_total = Donation::whereIn('status', ['redeemed'])->where('donation_to', auth()->user()->id)->sum('amount');
        $cashout = Cashout::with('donation_request')
            ->whereHas('donation_request', function($q) use ($user_id){
                $q->where('user_id', $user_id);
            });
        $cashouts = $cashout->latest()->get();
        $cashout_received = $cashout->sum('redeemed_amount');
        $cashout_commission = $cashout->sum('cash_out_commission');
        $cashout_fee = $cashout->sum('fee_amount');
        $amount_for_donate = $cashout->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
        $data = [
            'donation_send' => number_format($donation_send, 2),
            'donation_received' => number_format($donation_received, 2),
            'cashout_total' => number_format($cashout_total, 2),
            'cashout_received' => number_format($cashout_received, 2),
            'cashout_commission' => number_format($cashout_commission, 2),
            'cashout_fee' => number_format($cashout_fee, 2),
            'amount_for_donate' => number_format($amount_for_donate, 2),
        ];
        $data = (object)$data;
        return view('public.my-wallet', compact('cashouts', 'data'));
    }

    public function account_settings()
    {
        $settings = Setting::where('id', 1)->first();
        return view('public.account-settings', compact('settings'));
    }

    public function donationWishlistCreate(Request $request)
    {
        try {
            $rules = [
                'donation_request_id' => 'required|exists:donation_requests,id'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['success' => false, 'message' => $error], 200);
            }
            $insert = \javcorreia\Wishlist\Facades\Wishlist::add($request->donation_request_id, auth()->user()->id);
            if ($insert) {
                return response()->json(['success' => true, 'message' => 'Added successfully.'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Added failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 200);
        }
    }

    public function donationWishlistRemove(Request $request)
    {
        try {
            $rules = [
                'donation_request_id' => 'required|exists:donation_requests,id'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['success' => false, 'message' => $error], 200);
            }
            $insert = WishlistModel::where('item_id', $request->donation_request_id)->where('user_id', auth()->user()->id)->delete();
            if ($insert) {
                return response()->json(['success' => true, 'message' => 'Removed successfully.'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Removed failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 200);
        }
    }

    public function donation_request()
    {
        $categories = Category::get();
        $donation_amount = ApiHelper::referral_users();
        $now = Carbon::now();
        $date = Carbon::parse(auth()->user()->created_at);
        $diff = $date->diffInYears($now);
        $starts_from = $date->addYears($diff)->startOfDay();
        $ends_at = Carbon::parse($starts_from)->addYear()->subSecond();
        //$sub=Subscription::where("user_id",auth()->user()->id)->where("status","Success")
        //    ->where('starts_from', '<=', $now)->where('ends_at', '>=', $now)->first();
        return view('public.donation-request', compact('categories', 'donation_amount', 'starts_from', 'ends_at'));
    }

    public function donation_request_edit($id)
    {
        $data = DonationRequest::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();
        if($data) {
            $categories = Category::get();
            $donation_amount = ApiHelper::referral_users();
            $now = Carbon::now();
            $date = Carbon::parse(auth()->user()->created_at);
            $diff = $date->diffInYears($now);
            $starts_from = $date->addYears($diff)->startOfDay();
            $ends_at = Carbon::parse($starts_from)->addYear()->subSecond();
            /*$sub=Subscription::where("user_id",auth()->user()->id)->where("status","Success")
                ->where('starts_from', '<=', $now)->where('ends_at', '>=', $now)->first();*/
            return view('public.donation-request-edit', compact('data', 'categories', 'donation_amount', 'starts_from', 'ends_at'));
        }
        abort(404);
    }

    public function donation_request_delete($id)
    {
        $data = DonationRequest::withCount(['donors as donation_received' => function($query) {
            $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
        }])->where('id', $id)->first();

        if($data->user_id != auth()->user()->id) {
            return redirect()->back()->withError('You are not owner of this DoleUpp.');
        } elseif($data->donation_received > 0) {
            return redirect()->back()->withError('You not delete the DoleUpp Reel after receiving DoleUpp.');
        }

        $delete = DonationRequest::where('id', $id)->delete();
        if ($delete) {
            return redirect()->back()->withSuccess('Your DoleUpp request deleted successfully.');
        }
        return redirect()->back()->withError('Your DoleUpp request deleting failed.');
    }

    public function donationrequest(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'video' => 'required_if:id,null|mimes:mp4',
            'caption' => 'required_if:id,null|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            'Description' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
            //'donation_amount' => 'required|numeric|min:1|max:'.ApiHelper::referral_users()
        ],[
            'video.required_if' => 'The video is required.',
            'caption.required_if' => 'The caption field is required.',
            'donation_amount.required' => 'The DoleUpp amount is required.',
            'donation_amount.numeric' => 'The DoleUpp amount must be a numeric value.',
            'donation_amount.min' => 'The DoleUpp amount must be equal or greater then 1$',
            'donation_amount.max' => 'The DoleUpp amount must be equal or less then '.ApiHelper::referral_users().'$'
        ]);

        $input = $request->only('category_id', 'caption', 'Description');
        if ($request->file('video')) {
            $videoName = $request->video->store('/videos');
            $videoUrl = url('storage').'/'.$videoName;
            $input['video'] = $videoUrl;
            $videoFrom = explode('/', $videoName);
            $imageTo = uniqid('reel_', true).time().'.png';
            $thumbnail = url('storage/videos/thumbnail').'/'.$imageTo;

            $media = FFMpeg::open($videoName);
            $durationInSeconds = $media->getDurationInSeconds();

            if($durationInSeconds > 120)
            {
                return redirect()->back()->withError('Video length should by less then 2 Min.');
            }

            FFMpeg::fromDisk('videos')
            ->open($videoFrom[1])
            ->getFrameFromSeconds(1)
            ->export()
            ->toDisk('thumbnail')
            ->save($imageTo);
            $input['thumbnail'] = $thumbnail;
            $media_id = ApiHelper::videoVerification($videoUrl);
            if(!empty($media_id)) {
                $input['media_id'] = $media_id;
            }
        }
        $input['user_id'] = auth()->user()->id;
        $id = [
            'id' => $request->id
        ];
        $insert = DonationRequest::updateOrCreate($id, $input);
        if ($insert) {
            //echo json_encode(ApiHelper::videoVerification($insert->video));die();
            if ($insert->wasRecentlyCreated) {
                if ($request->is_prime == 'Yes') {
                    $settings = Setting::find(1);
                    $payer = new Payer();
                    $payer->setPaymentMethod('paypal');

                    $item = new Item();
                    $item->setName($insert->caption . ' #' . $insert->id)
                        ->setCurrency('USD')
                        ->setQuantity(1)
                        ->setPrice($settings->prime_donation_price);

                    $item_list = new ItemList();
                    $item_list->setItems(array($item));

                    $amount = new Amount();
                    $amount->setCurrency('USD')
                        ->setTotal($settings->prime_donation_price);

                    $transaction = new Transaction();
                    $transaction->setAmount($amount)
                        ->setItemList($item_list)
                        ->setDescription('Pay for Prime Reel');

                    $redirect_urls = new RedirectUrls();
                    $redirect_urls->setReturnUrl(URL::route('donationpaymentstatus'))
                        ->setCancelUrl(URL::route('donationpaymentstatus'));

                    $payment = new Payment();
                    $payment->setIntent('Sale')
                        ->setPayer($payer)
                        ->setRedirectUrls($redirect_urls)
                        ->setTransactions(array($transaction));
                    try {
                        $payment->create($this->_api_context);
                    } catch (\PayPal\Exception\PPConnectionException $ex) {
                        if (\Config::get('app.debug')) {
                            return redirect()->back()->withError('Connection Timeout');
                        } else {
                            return redirect()->back()->withError('Some error occur, sorry for inconvenient.');
                        }
                    }

                    foreach ($payment->getLinks() as $link) {
                        if ($link->getRel() == 'approval_url') {
                            $redirect_url = $link->getHref();
                            break;
                        }
                    }

                    Session::put('paypal_payment_id', $payment->getId());

                    if (isset($redirect_url)) {
                        DonationPayment::create([
                            'donation_request_id' => $insert->id,
                            'payment_id' => $payment->getId(),
                            'amount' => $settings->prime_donation_price
                        ]);
                        return Redirect::away($redirect_url);
                    }

                    return redirect()->back()->withError('Unknown error occurred');
                }
                return redirect()->back()->withSuccess('Your DoleUpp request is Submitted and will be posted on app once approved by admin.');
            } else {
                return redirect()->to(route('lazor-reels'))->withSuccess('Your DoleUpp request is updated successfully.');
            }
        }
        return redirect()->back()->withError('Your DoleUpp request submitting failed.');
    }

    public function donationPaymentStatus(Request $request)
    {
        $payment_id = Session::get('paypal_payment_id');

        $d_pay = DonationPayment::where('payment_id', $payment_id)->first();
        Session::forget('paypal_payment_id');
        if (empty($request->input('PayerID')) || empty($request->input('token')) || !$d_pay) {
            DonationPayment::where('payment_id', $payment_id)->update([
                'status' => 'Failed'
            ]);
            return Redirect::route('donation-request')->withWarning('Payment failed, Your DoleUpp request is Submitted and will be posted on app once approved by admin.');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
            DonationPayment::where('id', $d_pay->id)->update([
                'status' => 'Success'
            ]);
            DonationRequest::where('id', $d_pay->donation_request_id)->update(['is_prime' => 'Yes']);
            return Redirect::route('donation-request')->withSuccess('Payment success, Your DoleUpp request is Submitted and will be posted on app once approved by admin.');
        }

        DonationPayment::where('id', $d_pay->id)->update([
            'status' => 'Failed'
        ]);
		return Redirect::route('donation-request')->withWarning('Payment failed, Your DoleUpp request is Submitted and will be posted on app once approved by admin.');
    }

    public function donationMakePayment(Request $request)
    {
        //try {
            $request->validate([
                'donations' => 'required|array|min:1',
                'use_donation_amount' => 'nullable|in:Yes,No',
                'donations.*.donation_request_id' => 'required|exists:donation_requests,id',
                'donations.*.amount' => 'required|numeric|min:0',
                'video' => 'nullable|mimes:mp4,mov,ogg,qt,flv,avi,wmv',
            ],[
                'donations.*.amount.required' => 'The DoleUpp amount is required.',
                'donations.*.amount.numeric' => 'The DoleUpp amount must be a numeric value.',
                'donations.*.amount.min' => 'The DoleUpp amount must be equal or greater then 0$.'
            ]);

            if ($request->file('video')) {
                $videoName = $request->video->store('/videos');
                $videoUrl = url('storage').'/'.$videoName;
                $videoFrom = explode('/', $videoName);
                $imageTo = uniqid('reel_experience_', true).time().'.png';
                $thumbnail = url('storage/videos/thumbnail').'/'.$imageTo;

                FFMpeg::fromDisk('videos')
                    ->open($videoFrom[1])
                    ->getFrameFromSeconds(1)
                    ->export()
                    ->toDisk('thumbnail')
                    ->save($imageTo);
                $input = [
                    'video' => $videoUrl,
                    'thumbnail' => $thumbnail,
                    'user_id' => auth()->user()->id
                ];
            }

            $amount_for_donate = 0;
            if($request->use_donation_amount == 'Yes') {
                $amount_for_donate = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
                    $q->where('user_id', auth()->user()->id);
                })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
            }
            $d_amount = 0;
            foreach($request->donations as $d) {
                $d_amount += $d['amount'] ?? 0;
                $find = DonationRequest::withCount(['donors as donation_received' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                }])->withCount(['rating' => function($query) {
                    $query->select(DB::raw('COALESCE(avg(rating),0)'));
                }])->find($d['donation_request_id']);
                if($d['amount'] > ($find->donation_amount - $find->donation_received)){
                    return redirect()->back()->withInput()->withError("Your DoleUpp amount is higher than a user requested. Please adjust your donation amount.");
                }

                if ($request->file('video')) {
                    $id = [
                        'donation_request_id' => $d['donation_request_id'],
                        'user_id' => auth()->user()->id,
                        'created_at' => Carbon::now()
                    ];
                    $input['donation_request_id'] = $d['donation_request_id'];
                    $insert = Feedback::updateOrCreate($id, $input);
                }
            }

            $settings = Setting::find(1);
            $amount_to_pay = 0;
            $commission = $settings->admin_commission ?? 0;
            $d_amount = $d_amount+(($d_amount/100)*$commission);
            if($d_amount > 0 && $d_amount > $amount_for_donate)
            {
                // Cashout::with('donation_request')->whereHas('donation_request', function($q) {
                //     $q->where('user_id', auth()->user()->id);
                // })->whereDate('created_at', '>', Carbon::now()->subDays(7))->update(['fee_for_donation' => 0]);

                $settings = Setting::find(1);
                $payer = new Payer();
                $payer->setPaymentMethod('paypal');

                $items = [];
                $epayment_id = 'EPAYID-'.strtoupper(Str::random(30));
                foreach ($request->donations as $donation) {
                    $find = DonationRequest::find($donation['donation_request_id']);
                    if ($find) {
                        if(empty($donation['amount'])){
                            $donation['amount']=0;
                        }
                        $donation_amount = $donation['amount']+(($donation['amount']/100)*$commission);
                        if($donation_amount > 0) {
                            if($amount_for_donate > 0) {
                                /*if($amount_for_donate >= $donation['amount']) {
                                    $fee_for_donation = 0;
                                    $amount_from_wallet = $donation['amount'];
                                    $amount_for_donate = $amount_for_donate - $donation['amount'];
                                } else {
                                    $fee_for_donation = $donation['amount'] - $amount_for_donate;
                                    $amount_from_wallet = $amount_for_donate;
                                    $amount_for_donate = 0;
                                }*/
                                if($amount_for_donate >= $donation_amount) {
                                    $fee_for_donation = 0;
                                    $amount_from_wallet = $amount_for_donate;
                                    $admin_commission = $donation_amount-$donation['amount'];
                                    $amount_for_donate = $amount_for_donate - $donation_amount;
                                } else {
                                    $fee_for_donation = $donation_amount - $amount_for_donate;
                                    $amount_from_wallet = $amount_for_donate;
                                    $admin_commission = $donation_amount-$donation['amount'];
                                    $amount_for_donate = 0;
                                }
                                $price = $fee_for_donation;
                            } else {
                                $price = $donation_amount;
                                $admin_commission = $donation_amount-$donation['amount'];
                            }
                            $amount_to_pay += $price;
                            $insert = Donation::create([
                                'payment_id' => $epayment_id,
                                'donation_by' => auth()->user()->id,
                                'donation_to' => $find->user_id,
                                'donation_request_id' => $donation['donation_request_id'],
                                'amount' => $donation['amount'] ?? 0,
                                'amount_from_wallet' => $amount_from_wallet ?? 0,
                                'admin_commission' => $admin_commission ?? 0,
                                'status' => 'pending'
                            ]);
                            if($price > 0) {
                                $item = new Item();
                                $item->setName($insert->id)
                                    ->setCurrency('USD')
                                    ->setQuantity(1)
                                    ->setPrice($price);
                                $items[] = $item;
                            }
                        }
                        //WishlistModel::where('item_id', $donation['donation_request_id'])->where('user_id', auth()->user()->id)->delete();
                    }
                }

                //$handling_fee = ($amount_to_pay/100)*20;
                //$subTotal = $amount_to_pay-$handling_fee;

                $item_list = new ItemList();
                $item_list->setItems($items);

                /*$details = new Details();
                $details->setHandlingFee($handling_fee)
                    ->setSubtotal($subTotal);*/

                $amount = new Amount();
                $amount->setCurrency('USD')
                    ->setTotal($amount_to_pay);
                    //->setDetails($details);

                $transaction = new Transaction();
                $transaction->setAmount($amount)
                    ->setItemList($item_list)
                    ->setDescription('DoleUpp Payment');

                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(URL::route('donate.paymentstatus'))
                    ->setCancelUrl(URL::route('donate.paymentstatus'));

                $payment = new Payment();
                $payment->setIntent('Sale')
                    ->setPayer($payer)
                    ->setRedirectUrls($redirect_urls)
                    ->setTransactions(array($transaction));
                try {
                    $payment->create($this->_api_context);
                } catch (\PayPal\Exception\PPConnectionException $ex) {
                    if (\Config::get('app.debug')) {
                        return redirect()->back()->withError('Connection Timeout');
                    } else {
                        return redirect()->back()->withError('Some error occur, sorry for inconvenient.');
                    }
                }

                foreach($payment->getLinks() as $link) {
                    if($link->getRel() == 'approval_url') {
                        $redirect_url = $link->getHref();
                        break;
                    }
                }

                Session::put('paypal_payment_id', $payment->getId());

                if(isset($redirect_url)) {
                    Donation::where('payment_id', $epayment_id)->update(['payment_id' => $payment->getId()]);
                    // DonationPayment::create([
                    //     'donation_request_id' => $insert->id,
                    //     'payment_id' => $payment->getId(),
                    //     'amount' => $settings->prime_donation_price
                    // ]);
                    Session::put('paypalLink',$redirect_url);
                    return redirect("checkout/?orderId=".$insert->id."&type=donation&paymentId=".$payment->getId());
                    // return Redirect::away($redirect_url);
                }

                return redirect()->back()->withError('Unknown error occurred');

            } else {
                if($d_amount > 0) {
                    $cashouts = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
                        $q->where('user_id', auth()->user()->id);
                    })->whereDate('created_at', '>', Carbon::now()->subDays(7))->get();
                    foreach($cashouts as $cashout) {
                        if($d_amount >= $cashout->fee_for_donation) {
                            $fee_for_donation = 0;
                            $d_amount = $d_amount - $cashout->fee_for_donation;
                        } else {
                            $fee_for_donation = $cashout->fee_for_donation-$d_amount;
                            $d_amount = 0;
                        }
                        Cashout::where('id', $cashout->id)->update(['fee_for_donation' => $fee_for_donation]);
                    }
                    $epayment_id = 'EPAYID-'.strtoupper(Str::random(30));
                    foreach ($request->donations as $donation) {
                        $find = DonationRequest::find($donation['donation_request_id']);
                        if ($find) {
                            $donation_amount = $donation['amount']+(($donation['amount']/100)*$commission);
                            if($donation_amount > 0) {
                                Donation::create([
                                    'payment_id' => $epayment_id,
                                    'payment_status' => 'wallet',
                                    'donation_by' => auth()->user()->id,
                                    'donation_to' => $find->user_id,
                                    'donation_request_id' => $donation['donation_request_id'],
                                    'amount' => $donation['amount'],
                                    'amount_from_wallet' => $donation_amount,
                                    'admin_commission' => $donation_amount-$donation['amount'],
                                    'status' => 'earned'
                                ]);
                                WishlistModel::where('item_id', $donation['donation_request_id'])->where('user_id', auth()->user()->id)->delete();
                            }
                        }
                    }
                    return redirect()->back()->withSuccess('DoleUpp done successfully.');
                } else {
                    return redirect()->back()->withError('Total DoleUpp amount must be greater then 0$.');
                }
            }
//        } catch (\Exception $e) {
//            //echo $e->getMessage();
//            return redirect()->back()->withError($e->getMessage());
//        }
    }

    public function donatePaymentStatus(Request $request)
    {
        $payment_id = $request->paymentId;//Session::get('paypal_payment_id');

        $d_pay = Donation::where('payment_id', $payment_id)->get();

        Session::forget('paypal_payment_id');
        if (empty($request->input('PayerID')) || empty($request->input('token')) || !$d_pay) {
            return Redirect::route('lazor-reels')->withError('Payment failed, Please try again.');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));
        $result = $payment->execute($execution, $this->_api_context);
        //print_r($result->transactions[0]->item_list->items); die;
        if ($result->getState() == 'approved') {
            // DonationPayment::where('id', $d_pay->id)->update([
            //     'status' => 'Success'
            // ]);
            //$items = $result->transactions[0]->item_list->items;
            $donations = [];
            $d_amount = 0;
            foreach($d_pay as $k => $item) {
                $donations[$k] = $item->donation_request_id;
                $d_amount += $item->amount_from_wallet;
                //$donation = Donation::find($item->donation_request_id);
                Donation::where('id', $item->id)->update(['status' => 'earned']);
                WishlistModel::where('item_id', $item->donation_request_id)->where('user_id', auth()->user()->id)->delete();
            }
            //die;
            $cashouts = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
                $q->where('user_id', auth()->user()->id);
            })->whereDate('created_at', '>', Carbon::now()->subDays(7))->get();
            foreach($cashouts as $cashout) {
                if($d_amount > 0) {
                    if($d_amount >= $cashout->fee_for_donation) {
                        $fee_for_donation = 0;
                        $d_amount = $d_amount - $cashout->fee_for_donation;
                    } else {
                        $fee_for_donation = $cashout->fee_for_donation-$d_amount;
                        $d_amount = 0;
                    }
                    Cashout::where('id', $cashout->id)->update(['fee_for_donation' => $fee_for_donation]);
                } else {
                    break;
                }
            }
            //return Redirect::route('reels.rating', ['donations' => implode(",", $donations)])->withSuccess('Payment success, Your DoleUpp is Submitted.');
            return Redirect::route('subscription.payment-status', ['success' => '1', 'type' => 'donation', 'donations' => implode(",", $donations)])->withSuccess('Payment success, Your DoleUpp is Submitted.');
        }

        Donation::where('payment_id', $payment_id)->update(['status' => 'failed']);
		//return Redirect::route('my-holding-area')->withWarning('Payment failed, Please try again.');
        return Redirect::route('subscription.payment-status', ['success' => '0', 'type' => 'donation', 'donations' => implode(",", $donations)])->withWarning('Payment failed, Please try again.');
    }

    public function donation_cashout(Request $request)
    {
        $cashouts = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'donation_for_redeem'])->where('status', 'Approved')
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
            }])->with(['user', 'category', 'donation_for_redeem'])
            ->has('donation_for_redeem', '>', 0)
            ->where('user_id', auth()->user()->id);
            if($request->ids) {
                $ids = explode(',', $request->ids);
                $cashouts = $cashouts->whereIn('id', $ids);
            }
        $cashouts = $cashouts->latest()->get();
        $settings = Setting::where('id', 1)->first();
        $total_cashout = Donation::whereIn('status', ['redeemed'])->where('donation_to', auth()->user()->id)->sum('amount');
        if($request->ids) {
            if(count($cashouts) > 0) {
                return view('public.ready-to-cashout', compact('cashouts', 'settings', 'total_cashout'));
            }
            return redirect()->to(route('donation.cashout'));
        }
        return view('public.cashouts', compact('cashouts', 'settings', 'total_cashout'));
    }

    public function readyToCashout(Request $request)
    {
        $rules = [
            'donations' => 'required|array|min:1',
            'donations.*.id' => 'required|exists:donation_requests,id',
            'donations.*.cashout' => 'nullable',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $error = '';
            if (!empty($validator->errors())) {
                $error = $validator->errors()->first();
            }
            return redirect()->back()->withInput()->withError($error);
        }

        $donations = $request->only('donations');
        $donation_req = [];
        $answers = 0;
        foreach($donations['donations'] as $key => $item) {
            if (isset($item['cashout']) && $item['cashout'] == 1) {
                $answers++;
                $donation_req[] = $item['id'];
            }
        }

        if($answers >= 1) {
            return redirect()->to(route('donation.cashout',['ids' => implode(',',$donation_req)]));
        }
        return redirect()->back()->withInput()->withError('Select at least one.');
    }

    public function donationCashOut(Request $request)
    {
        $rules = [
            'donations' => 'required|array|min:1',
            'donations.*.id' => 'required|exists:donation_requests,id',
            'donations.*.cashout' => 'nullable' //'required|numeric|min:0',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $error = '';
            if (!empty($validator->errors())) {
                $error = $validator->errors()->first();
            }
            return redirect()->back()->withInput()->withError($error);
        }

        try {
            $donations = $request->only('donations');
            $donation_req = [];
            $answers = 0;
            foreach($donations['donations'] as $key => $item) {
                if (isset($item['cashout']) && $item['cashout'] == 1)
                    $answers++;
                    $donation_req[] = $item['id'];
            }

            if($answers >= 1) {
                $settings = Setting::find(1);
                $cashouts = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'donation_for_redeem'])->where('status', 'Approved')
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
                }])->with(['user', 'category', 'donation_for_redeem'])
                ->has('donation_for_redeem', '>', 0)
                ->whereIn('id', $donation_req)
                ->where('user_id', auth()->user()->id)->latest()->get();

                foreach($cashouts as $donation) {
                    $cashout_commission = ($donation->donation_earned/100)*$settings->cash_out_commission;
                    $cashout_fee = ($donation->donation_earned/100)*$settings->cash_out_fee;
                    $redeemed_amount = $donation->donation_earned-($cashout_fee+$cashout_commission);
                    $payout = $this->createPayout('Cash out to '.auth()->user()->name.' of reel #'.$donation->id, auth()->user()->email, auth()->user()->name, $redeemed_amount);
                    if($payout->getData()->status == true) {
                        foreach($donation->donation_for_redeem as $dfr) {
                            Donation::where('id', $dfr->id)->update([
                                'status' => 'redeemed'
                            ]);
                        }
                        $data = Payout::get($payout->getData()->message, $this->_api_context);
                        Cashout::create([
                            'donation_request_id' => $donation->id,
                            'redeemed_amount' => $redeemed_amount,
                            'cash_out_commission' => $cashout_commission,
                            'fee_amount' => $cashout_fee,
                            'fee_for_donation' => $cashout_fee,
                            'status' => $data->getItems()[0]->transaction_status ?? '',
                            'batch_id' => $payout->getData()->message
                        ]);
                    } else {
                        return redirect()->back()->withInput()->withError('Cash out failed, Please try after some time or contact to support team.');
                    }
                }
                return redirect()->to(route('donation.cashout'))->withSuccess('Cashout successfully.');
            }
            return redirect()->back()->withInput()->withError('Cash out failed, Please try after some time or contact to support team.');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function amountForDonate()
    {
        $amount_for_donate = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
            $q->where('user_id', auth()->user()->id);
        })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
        return $amount_for_donate;
    }

    public function reel_details($id)
    {
        $reel = DonationRequest::withCount(['views', 'comments', 'shares', 'real_donors', 'donors', 'wishlist'])
        ->where(function ($q){
            $q->where('status', 'Approved')->orWhere('user_id', auth()->user()->id);
        })->withCount(['rating' => function($query) {
                $query->select(DB::raw('COALESCE(avg(rating),0)'));
        }])
        ->withCount(['donors as donation_received' => function($query) {
            $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
        }])->find($id);
        if($reel) {
            $shareReel = \Share::page(
                route('reels.show', ['slug' => $reel->id]),
                $reel->caption,
            )
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->whatsapp()
            ->reddit();

            return view('public.reel-details', compact('reel', 'shareReel'));
        }
        abort(404);
    }

    public function reel_views(Request $request)
    {
        try {
            $rules = [
                'donation_request_id' => 'required|exists:donation_requests,id'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => $error], 200);
            }

            $input = $request->only('donation_request_id');
            $input['user_id'] = auth()->user()->id ?? 1;
            $id = [
                'user_id' => auth()->user()->id ?? 1,
                'donation_request_id' => $request->donation_request_id
            ];
            $insert = View::updateOrCreate($id ,$input);
            if ($insert) {
                return response()->json(['success' => true, 'statusCode' => 200, 'message' => 'Your View is Submitted.'], 200);
            }
            return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Your view submit failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'statusCode' => 422, 'message' => $e->getMessage()], 200);
        }
    }

    public function reel_shares(Request $request)
    {
        try {
            $rules = [
                'donation_request_id' => 'required|exists:donation_requests,id'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => $error], 200);
            }

            $input = $request->only('donation_request_id');
            $input['user_id'] = auth()->user()->id ?? 1;
            $id = [
                'user_id' => auth()->user()->id ?? 1,
                'donation_request_id' => $request->donation_request_id
            ];
            $insert = Share::updateOrCreate($id ,$input);
            if ($insert) {
                return response()->json(['success' => true, 'statusCode' => 200, 'message' => 'Your Share is Submitted.'], 200);
            }
            return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Your Share submit failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'statusCode' => 422, 'message' => $e->getMessage()], 200);
        }
    }

    public function donationComment(Request $request)
    {
        try {
            $rules = [
                'donation_request_id' => 'required|exists:donation_requests,id',
                'comment_type' => 'required|in:text,image',
                'comment' => 'required|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'parent_id' => 'nullable|exists:comments,id',
                'tag_id' => 'nullable|exists:users,id',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => $error], 200);
            }

            $input = $request->only('donation_request_id', 'comment_type', 'comment', 'parent_id', 'tag_id');
            // if($request->comment_type == 'image') {
            //     $filename = basename($request->comment);
            //     $imageName = Image::make($request->comment)->save(public_path('storage/images/comment/' . $filename));
            //     // $imageName = $request->comment->store('images/comment');
            //     $input['comment'] = asset($imageName);
            // }

            $input['user_id'] = auth()->user()->id;
            $insert = Comment::create($input);
            if ($insert) {
                return response()->json(['success' => true, 'statusCode' => 200, 'message' => 'Your comment is Submitted.'], 200);
            }
            return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Your comment submit failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'statusCode' => 422, 'message' => $e->getMessage()], 200);
        }
    }

    public function donationCommentGif(Request $request)
    {
        try {
            $request->validate([
                'donation_request_id' => 'required|exists:donation_requests,id',
                'comment_type' => 'required|in:text,image',
                'comment' => 'required',
                'parent_id' => 'nullable|exists:comments,id',
                'tag_id' => 'nullable|exists:users,id',
            ]);

            $verify = ApiHelper::imageVerification($request->comment);
            if ($verify != 'success') {
               return response()->json(['statusCode' => 422, 'message' => 'Image reject to upload because of ' . $verify . ', Please use another one.'], 200);
            }

            $input = $request->only('donation_request_id', 'comment_type', 'comment', 'parent_id', 'tag_id');
            // if($request->comment_type == 'image') {
            //     $filename = basename($request->comment);
            //     $imageName = Image::make($request->comment)->save(public_path('storage/images/comment/' . $filename));
            //     // $imageName = $request->comment->store('images/comment');
            //     $input['comment'] = asset($imageName);
            // }

            $input['user_id'] = auth()->user()->id;
            $insert = Comment::create($input);
            // if ($insert) {
            //     return redirect()->back()->withSuccess('Your Comment is Submitted.');
            // }
            // return redirect()->back()->withError('Your Comment submittion failed.');
            if ($insert) {
                return response()->json(['success' => true, 'statusCode' => 200, 'message' => 'Your comment is Submitted.'], 200);
            }
            return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Your comment submit failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'statusCode' => 422, 'message' => $e->getMessage()], 200);
        }
    }

    public function news(Request $request)
    {
        $news = News::latest()->paginate(6);
        if ($request->ajax()) {
            $view = view('public.news', compact('news'))->render();
            return response()->json(['html'=>$view]);
        }
        return view('public.lazor-news', compact('news'));
    }

    public function news_detail($slug)
    {
        $news = News::where('slug', $slug)->first();
        if($news) {
            return view('public.lazor-news-details', compact('news'));
        }
        abort(404);
    }

    public function community(Request $request)
    {
        $news = News::latest()->paginate(6);
        if ($request->ajax()) {
            $view = view('public.communities', compact('news'))->render();
            return response()->json(['html'=>$view]);
        }
        return view('public.community', compact('news'));
    }

    public function community_detail($slug)
    {
        $news = News::where('slug', $slug)->first();
        if($news) {
            return view('public.community-details', compact('news'));
        }
        abort(404);
    }

    public function notification(Request $request)
    {
        try {
            $rules = [
                'notification' => 'required|in:Yes,No'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $input = $request->only('notification');
            $update = User::where('id', auth()->user()->id)->update($input);
            if($update) {
                $data = User::find(auth()->user()->id);
                $data = setJsonData($data->toArray());
                return response()->json(['success' => true, 'statusCode' => 200, 'message' => 'Notification update successfully.', 'data' => $data], 200);
            }
            return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Notification updation failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'statusCode' => 422, 'message' => $e->getMessage()], 200);
        }
    }

    public function sightengineVideo(Request $request)
    {
        $donation_requests = DonationRequest::whereNotNull('media_id')->where(function($q){
            $q->where('content_status', 'ongoing')->orWhereNull('content_status');
        })->get();
        foreach ($donation_requests as $ds) {
            $output = ApiHelper::videoVerify($ds->media_id);
            // $input = [
            //     'content_status' => $output['output']['data']['status'],
            //     'action' => $output['output']['summary']['action'],
            //     'reject_prob' => $output['output']['summary']['reject_prob'],
            //     'reject_reason' => $output['output']['summary']['reject_reason'],
            // ];
            $reject_reason = implode(",", array_column($output['output']['summary']['reject_reason'], "text"));
            $input = [
                'content_status' => $output['output']['data']['status'],
                'action' => $output['output']['summary']['action'],
                'reject_prob' => $output['output']['summary']['reject_prob'],
                'reject_reason' => $reject_reason,
            ];
            if($output['output']['data']['status'] == 'accept') {
                $input['status'] = 'Approved';
            }
            if($output['status'] == 'success') {
                DonationRequest::where('id', $ds->id)->update($input);
            }
        }
        return response()->json(['success' => true, 'statusCode' => 200, 'message' => 'Video verification update successfully.'], 200);
    }

    public function createPayout($email_subject, $email, $name, $amount)
    {
        // Create a new instance of Payout object
        $payouts = new Payout();
        $senderBatchHeader = new PayoutSenderBatchHeader();
        // #### Batch Header Instance
        $senderBatchHeader->setSenderBatchId(rand(1111111, 999999).uniqid())
            ->setEmailSubject($email_subject);

        $senderItem = new PayoutItem(
            array(
                "recipient_type" => "EMAIL",
                "receiver" => $email,
                "note" => "Payout sent to ".$name,
                "sender_item_id" => "cashout_".rand(1111111, 999999).uniqid(),
                "amount" => array(
                    "value" => $amount,
                    "currency" => "USD"
                )

            )
        );

        $payouts->setSenderBatchHeader($senderBatchHeader)
            ->addItem($senderItem);
        // For Sample Purposes Only.
        $request = clone $payouts;

        // ### Create Payout
        try {
            // For single
            // $output = $payouts->createSynchronous($this->_api_context);
            // For Multiple
            $output = $payouts->create(null, $this->_api_context);

        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
        // return $output;
        return response()->json(['status' => true, 'message' => $output->getBatchHeader()->getPayoutBatchId()]);
    }

    // public function getPayoutStatus()
    // {
    //     $payoutBatch = new \PayPal\Api\PayoutBatch();
    //     $payoutBatchId = $payoutBatch->getBatchHeader()->getPayoutBatchId();
    //     try {
    //         $output = \PayPal\Api\Payout::get($payoutBatchId, $this->_api_context);
    //     } catch (Exception $ex) {
    //         // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    //         ResultPrinter::printError("Get Payout Batch Status", "PayoutBatch", null, $payoutBatchId, $ex);
    //         exit(1);
    //     }
    // }

    public function donationToLazor(Request $request)
    {
        $request->validate([
            'categories' => 'required|array|min:1',
            'description' => 'nullable',
            'subscription_type' => 'required|in:monthly,onetime',
            'donation_amount' => 'required_if:subscription_type,==,onetime|numeric|min:50',
            'donation_plan' => 'required_if:subscription_type,==,monthly|numeric',
        ]);

        if($request->subscription_type == 'monthly')
        {
            // Create new agreement
            $agreement = new Agreement();
            $agreement->setName('App Name Monthly Subscription Agreement')
                ->setDescription('Basic Subscription')
                ->setStartDate(\Carbon\Carbon::now()->addMinutes(5)->toIso8601String());

            // Set plan id
            $plan = new Plan();
            $plan->setId($this->plan_id);
            $agreement->setPlan($plan);

            // Add payer type
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $agreement->setPayer($payer);

            try {
                // Create agreement
                $agreement = $agreement->create($this->_api_context);

                // Extract approval URL to redirect user
                $approvalUrl = $agreement->getApprovalLink();
                return redirect($approvalUrl);
//                if(isset($approvalUrl)) {
//                    LazorDonation::create([
//                        'user_id' => auth()->user()->id,
//                        'payment_id' => 'PAYID-'.Str::random(20),//$agreement->id,
//                        'categories' => implode(", ", $request->categories),
//                        'description' => $request->description,
//                        'subscription_type' => $request->subscription_type,
//                        'amount' => 50,
//                        'amount_for_donate' => 50,
//                        'status' => 'pending'
//                    ]);
//                    return redirect($approvalUrl);
//                }
//                return Redirect::back()->withInput()->withError('Unknown error occurred');
            } catch (PayPal\Exception\PayPalConnectionException $ex) {
                return redirect()->back()->withInput()->withError('Paypal connection issue, Please try after some time.');
//                echo $ex->getCode();
//                echo $ex->getData();
//                die($ex);
            } catch (Exception $ex) {
                return redirect()->back()->withInput()->withError('DoleUpp request failed, Please try after some time.');
            }
            // return redirect()->back()->withInput()->withSuccess('DoleUpp done successfully.');
        } else {
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $item = new Item();
            $item->setName('One Time Subscription Fee')
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($request->donation_amount);

            $item_list = new ItemList();
            $item_list->setItems(array($item));

            $amount = new Amount();
            $amount->setCurrency('USD')
                ->setTotal($request->donation_amount);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Donate to DoleUpp for one time subscription');

            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(URL::route('donatation.lazorstatus'))
                ->setCancelUrl(URL::route('donatation.lazorstatus'));

            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    return Redirect::back()->withInput()->withError("Connection Timeout.");
                } else {
                    return Redirect::back()->withInput()->withError('Some error occur, sorry for inconvenient');
                }
            } catch (\PayPal\Exception\PayPalConnectionException $ex) {
                // dd($ex);
                return Redirect::back()->withInput()->withError("Something went wrong, Please try after some time.");
            }

            foreach($payment->getLinks() as $link) {
                if($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }

            Session::put('paypal_payment_id', $payment->getId());

            if(isset($redirect_url)) {
                LazorDonation::create([
                    'user_id' => auth()->user()->id,
                    'payment_id' => $payment->getId(),
                    'categories' => implode(", ", $request->categories),
                    'description' => $request->description,
                    'subscription_type' => $request->subscription_type,
                    'amount' => $request->donation_amount,
                    'amount_for_donate' => $request->donation_amount,
                    'status' => 'pending'
                ]);
                return Redirect::away($redirect_url);
            }
            return Redirect::back()->withInput()->withError('Unknown error occurred');
        }
    }

    public function donationToLazorStatus(Request $request)
    {
        $payment_id = $request->paymentId;//Session::get('paypal_payment_id');

        $d_pay = LazorDonation::where('payment_id', $payment_id)->first();

        Session::forget('paypal_payment_id');
        if (empty($request->input('PayerID')) || empty($request->input('token')) || !$d_pay) {
            return Redirect::route('home')->withError('Payment failed, Please try again.');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {
            LazorDonation::where('id', $d_pay->id)->update([
                'status' => 'success'
            ]);
            return Redirect::route('corporate.success')->withSuccess('Payment success, Your DoleUpp is Submitted.');
        }

        LazorDonation::where('payment_id', $payment_id)->update(['status' => 'failed']);
		return Redirect::route('corporate.failed')->withWarning('Payment failed, Please try again.');
    }

    public function lazorDontation()
    {
        $lazor_donations = LazorDonation::where('user_id', auth()->user()->id)->latest()->get();
        return view('public.my-corporate-donations', compact('lazor_donations'));
    }

    public function lazorDontationDetails($id)
    {
        $lazor_donation = LazorDonation::find($id);
        if($lazor_donation) {
            $donations = Donation::with('donation_to', 'donation_request.user', 'donation_request.category')
                ->where('donation_by', auth()->user()->id)->where('lazor_donation_id', $id)->latest()->get();
            return view('public.lazor-donation', compact('lazor_donation', 'donations'));
        }
        abort(404);
    }

    // Paypal Payout
    public function create_plan(){

        // Create a new billing plan
        $plan = new Plan();
        $plan->setName('DoleUpp App DoleUpp Monthly Billing')
            ->setDescription('Monthly Subscription to the DoleUpp App')
            ->setType('infinite');

        // Set billing plan definitions
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
            ->setType('REGULAR')
            ->setFrequency('Month')
            ->setFrequencyInterval('1')
            ->setCycles('0')
            ->setAmount(new Currency(array('value' => 50, 'currency' => 'USD')));

        // Set merchant preferences
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl(route('paypal.return'))
            ->setCancelUrl(route('paypal.return'))
            ->setAutoBillAmount('yes')
            ->setInitialFailAmountAction('CONTINUE')
            ->setMaxFailAttempts('0');

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);

        //create the plan
        try {
            $createdPlan = $plan->create($this->_api_context);

            try {
                $patch = new Patch();
                $value = new PayPalModel('{"state":"ACTIVE"}');
                $patch->setOp('replace')
                    ->setPath('/')
                    ->setValue($value);
                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);
                $createdPlan->update($patchRequest, $this->_api_context);
                $plan = Plan::get($createdPlan->getId(), $this->_api_context);

                // Output plan id
                echo 'Plan ID:' . $plan->getId();
            } catch (PayPal\Exception\PayPalConnectionException $ex) {
                echo $ex->getCode();
                echo $ex->getData();
                die($ex);
            } catch (Exception $ex) {
                die($ex);
            }
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    }

    public function paypalRedirect(){
        // Create new agreement
        $agreement = new Agreement();
        $agreement->setName('App Name Monthly Subscription Agreement')
            ->setDescription('Basic Subscription')
            ->setStartDate(\Carbon\Carbon::now()->addMinutes(5)->toIso8601String());

        // Set plan id
        $plan = new Plan();
        $plan->setId($this->plan_id);
        $agreement->setPlan($plan);

        // Add payer type
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        try {
            // Create agreement
            $agreement = $agreement->create($this->_api_context);

            // Extract approval URL to redirect user
            $approvalUrl = $agreement->getApprovalLink();

            return redirect($approvalUrl);
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    }

    public function paypalReturn(Request $request){

        $token = $request->token;
        $agreement = new \PayPal\Api\Agreement();

        try {
            // Execute agreement
            $result = $agreement->execute($token, $this->_api_context);
            $user = auth()->user();
            $user->paypal_agreement_status = '1';
            $user->paypal_agreement_date = Carbon::now();
            if(isset($result->id)){
                $user->paypal_agreement_id = $result->id;
            }
            $user->save();

            // echo 'New Subscriber Created and Billed - '.$result->id;
            return Redirect::route('home')->withSuccess('Your Subscriber Created and Billed.');
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return Redirect::route('home')->withError('You have either cancelled the request or your session has expired.');
            // echo 'You have either cancelled the request or your session has expired';
        }
    }

    public function paypalCancel()
    {
        $agreementId = auth()->user()->paypal_agreement_id;
        $agreement = new Agreement();

        $agreement->setId($agreementId);
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Cancel the agreement");

        try {
            $agreement->cancel($agreementStateDescriptor, $this->_api_context);
            $cancelAgreementDetails = Agreement::get($agreement->getId(), $this->_api_context);
            //echo 'Subscription cancelled';
            $user = auth()->user();
            $user->paypal_agreement_status = '3';
            $user->save();

            return Redirect::route('home')->withSuccess('Subscription cancelled successfully.');

        } catch (Exception $ex) {
            return Redirect::route('home')->withError('You have either cancelled the request or your session has expired.');
        }
    }

    public function reelsRating(Request $request)
    {
        //if($request->donations) {
            $donations = $request->donations;
            $type = $request->type ?? 'donation';
            return view('public.reels-rating', compact('donations', 'type'));
        //}
        //abort(403);
    }

    public function reelsRatingPost(Request $request)
    {
        $rules = [
            //'donation_request_id' => 'required',//|exists:donation_requests,id',
            'rating' => 'required|in:0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $error = '';
            if (!empty($validator->errors())) {
                $error = $validator->errors()->first();
            }
            return redirect()->back()->withError($error);
        }

        $donations = explode(',', $request->donation_request_id);
        foreach ($donations as $kay => $donation) {
            $id = [
                'donation_request_id' => $donation,
                'user_id' => auth()->user()->id
            ];
            $input = $request->only('rating');
            $input['donation_request_id'] = $donation;
            $input['user_id'] = auth()->user()->id;
            $insert = Rating::updateOrCreate($id, $input);
        }

        if ($insert) {
            $type = $request->type ?? 'donation';
            return redirect(route('success.rating',['type' => $type]))->withSuccess('Your Rating is Submitted.');
        }
        return redirect()->back()->withError('Your Rating not submit, Please try again.');
    }

    public function successRating(Request $request)
    {
        $badge='';
        $donation_send=0;
        if(auth()->user()) {
            $donation_send = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_by', auth()->user()->id)->sum('amount');
            if ($donation_send >= 500 && $donation_send < 5000) {
                $badge = 'bronze';
            } elseif ($donation_send >= 5000 && $donation_send < 50000) {
                $badge = 'silver';
            } elseif ($donation_send >= 50000 && $donation_send < 100000) {
                $badge = 'gold';
            } elseif ($donation_send >= 100000 && $donation_send < 1000000) {
                $badge = 'platinum';
            } elseif ($donation_send >= 1000000) {
                $badge = 'diamond';
            } else {
                $badge = '';
            }
        }
        $type = $request->type ?? 'donation';
        return view('subscription.success-rating', compact('type','badge','donation_send'));
    }

    public function checkout(Request $request)
    {
        $orderId = $request->orderId;
        $type = $request->type;
        $paymentId = $request->paymentId;
        $donations='';
        $amount=0;
        if($type=="donation")
        {
            $d_pay = Donation::where('payment_id', $paymentId)->get();
            if (!$d_pay) {
                return Redirect::route('lazor-reels')->withError('Payment failed, Please try again.');
            }
            $donations = [];
            $d_amount = 0;
            foreach($d_pay as $item) {
                $donations[]=$item->id;
                $amt = ($item->amount+$item->admin_commission)-$item->amount_from_wallet;
                $d_amount += $amt;
            }
            $donations=implode(",", $donations);
            $amount = round($d_amount, 2);
        }
        if($type=="subscription")
        {
            $donation=Subscription::find($orderId);
            // print_r($donation); exit;
            $amount=round($donation->price, 2);
            $donations='';
        }
        if($type=="corporation")
        {
            $donation=LazorDonation::where('payment_id',$paymentId)->first();
            $amount=round($donation->amount, 2);
            $donations=$donation->id;
        }
        return view("checkout",compact("orderId", "type", "amount", "paymentId", "donations"));
    }

    public function corporateCategories()
    {
        $categories=Category::all();
        return view('public.corporate-categories', compact('categories'));
    }

    public function corporateDonation(Request $request)
    {
        try {
            $request->validate([
                'categories' => 'required|array',
            ]);
            $categories = Category::all();
            if ($request->categories) {
                $categories = $request->categories;
            }
            return view('public.corporate-donation', compact('categories'));
        } catch (Exception $ex) {
            return Redirect::route('corporate.categories')->withError('Please select al least a donation category.');
        }
    }

    public function corporateDonationPost(Request $request)
    {
        $request->validate([
            'categories' => 'required|array|min:1',
            'name' => 'required',
            'donation_amount' => 'required|numeric|min:50',
        ]);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName('One Time Subscription Fee')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($request->donation_amount);

        $item_list = new ItemList();
        $item_list->setItems(array($item));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($request->donation_amount);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Donate to DoleUpp for one time subscription');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('donatation.lazorstatus'))
            ->setCancelUrl(URL::route('donatation.lazorstatus'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                return Redirect::back()->withInput()->withError("Connection Timeout.");
            } else {
                return Redirect::back()->withInput()->withError('Some error occur, sorry for inconvenient');
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return Redirect::back()->withInput()->withError("Something went wrong, Please try after some time.");
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        Session::put('paypal_payment_id', $payment->getId());

        if(isset($redirect_url)) {
            $insert=LazorDonation::create([
                'user_id' => auth()->user()->id,
                'payment_id' => $payment->getId(),
                'categories' => implode(", ", $request->categories),
                'description' => $request->name,
                'subscription_type' => 'onetime',
                'amount' => $request->donation_amount,
                'amount_for_donate' => $request->donation_amount,
                'status' => 'pending'
            ]);
            //return Redirect::away($redirect_url);
            Session::put('paypalLink',$redirect_url);
            return redirect("checkout/?orderId=".$insert->id."&type=corporation&paymentId=".$payment->getId());

        }
        return Redirect::back()->withInput()->withError('Unknown error occurred');
    }

    public function reelShareModel(Request $request)
    {
        $id = $request->id;
        $caption = $request->caption;
        $view = view('public.share_reel', compact('id','caption'))->render();
        return response()->json(['html'=>$view]);
    }

    public function corporateSuccess(Request $request)
    {
        return view('subscription.corporate-success');
    }

    public function corporateFailed(Request $request)
    {
        return view('subscription.corporate-failed');
    }

    public function privacy_policy_app()
    {
        return view('app.privacy-policy');
    }

    public function terms_and_conditions_app()
    {
        return view('app.terms-and-conditions');
    }

    public function guarantee_policy_app()
    {
        return view('app.guarantee-policy');
    }

    public function makeOnline(Request $request)
    {
        try {
            $input = $request->only('latitude','longitude');
            $input['live_status'] = 'online';
            $input['live_at'] = Carbon::now();
            $update = User::where('id', auth()->user()->id)->update($input);
            if ($update) {
                return response()->json(['status' => true, 'message' => 'Your live status update successfully.'], 200);
            }
            return response()->json(['status' => false, 'message' => 'Your live status not update.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong.'], 200);
        }
    }
}
