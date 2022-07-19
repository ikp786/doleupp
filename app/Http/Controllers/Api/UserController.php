<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Mail\CommonMail;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\CardDetail;
use App\Models\Cashout;
use App\Models\Category;
use App\Models\City;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Donation;
use App\Models\DonationRequest;
use App\Models\DonationRequestReport;
use App\Models\Faq;
use App\Models\FcmToken;
use App\Models\Feedback;
use App\Models\LazorDonation;
use App\Models\News;
use App\Models\Notification;
use App\Models\Rating;
use App\Models\Reason;
use App\Models\Referral;
use App\Models\Role;
use App\Models\SecurityQuestion;
use App\Models\Setting;
use App\Models\Share;
use App\Models\Subscription;
use App\Models\UpdateSetting;
use App\Models\User;
use App\Models\UserSecurityQuestion;
use App\Models\View;
use App\Models\Wishlist as WishlistModel;
use Carbon\Carbon;
use DB;
use URL;
use Str;
use Redirect;
use Exception;
use Illuminate\Http\Request;
use Image;
use javcorreia\Wishlist\Facades\Wishlist;
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
use Validator;
use VideoThumbnail;
use FFMpeg;

class UserController extends Controller
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

    public function profileCreate(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:3|max:255|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                // 'username' => 'required|without_spaces|max:16|unique:users,username|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'password' => 'required|min:6',
                // 'university' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                // 'occupation' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'address' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'state' => 'nullable|min:2|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'country' => 'nullable|min:2|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'address_code' => 'nullable|banned_words|email_not_allowed|website_not_allowed',
                'email' => 'required|email|unique:users,email',
                'dob' => 'required|date_format:m/d/Y|before_or_equal:'.Carbon::now()->subYears(4)->format('m/d/Y'),
                'about' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'image' => 'nullable',
                'referral_code' => 'nullable|exists:users,referral_code',
                //'image' => 'nullable|regex:/^data:image/'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error]);
            }

            $input = $request->only('name', 'username', 'password', 'university', 'occupation', 'address', 'state', 'country', 'address_code', 'email', 'about');
            $input['dob'] = Carbon::parse($request->dob)->format('Y-m-d');
            $input['password'] = bcrypt($input['password']);
            $input['username'] =$input['email'];
            $input['country'] = $request->country ?? 'US';
            $input['referral_code'] = ApiHelper::toRef(auth()->user()->id);
            if($request->image) {
                if($request->hasFile('image')) {
                    $imageName = $request->image->store('images/profile');
                    $input['image'] = asset('storage/'. $imageName);
                } else {
                    $folderPath = "/storage/images/profile/";
                    $img_parts = explode(";base64,", $request->image);
                    $img_type_aux = explode("image/", $img_parts[0]);
                    $img_type = $img_type_aux[1];
                    $img_base64 = base64_decode($img_parts[1]);
                    $image = $folderPath . uniqid() . '.'.$img_type;
                    file_put_contents(public_path().$image, $img_base64);
                    $input['image'] = asset($image);
                }
               /*  $verify = ApiHelper::imageVerification($input['image']);
                if ($verify != 'success') {
                    return response()->json(['statusCode' => 422, 'message' => 'Image reject to upload because of ' . $verify . ', Please use another one.'], 200);
                } */
            }
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
                if($request->device_token){
                    $fcm = ['token' => $request->device_token];
                    $fcm_token = ['token' => $request->device_token, 'user_id' => auth()->user()->id];
                    FcmToken::updateOrCreate($fcm,$fcm_token);
                }
                $data = User::find(auth()->user()->id);
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Profile updated successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Profile updation failed.'], 200);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function profileEdit(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:3|max:255|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                // 'username' => 'required|without_spaces|max:16|unique:users,username',
                // 'password' => 'required|min:6',
                'university' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'occupation' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'address' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'state' => 'nullable|min:2|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'country' => 'nullable|min:2|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'address_code' => 'nullable|banned_words|email_not_allowed|website_not_allowed',
                // 'email' => 'required|email|unique:users,email',
                'dob' => 'nullable|date_format:m/d/Y|before_or_equal:'.Carbon::now()->subYears(4)->format('m/d/Y'),
                'about' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'image' => 'nullable'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error]);
            }

            $input = $request->only('name', 'university', 'occupation', 'address', 'state', 'country', 'address_code', 'about');
            if($request->dob) {
                $input['dob'] = date('Y-m-d', strtotime($request->dob));
            }
            if($request->image) {
                if($request->hasFile('image')) {
                    $imageName = $request->image->store('images/profile');
                    $input['image'] = asset('storage/'. $imageName);
                } else {
                    $folderPath = "/storage/images/profile/";
                    $img_parts = explode(";base64,", $request->image);
                    $img_type_aux = explode("image/", $img_parts[0]);
                    $img_type = $img_type_aux[1];
                    $img_base64 = base64_decode($img_parts[1]);
                    $image = $folderPath . uniqid() . '.'.$img_type;
                    file_put_contents(public_path().$image, $img_base64);
                    $input['image'] = asset($image);
                }
               /*  $verify = ApiHelper::imageVerification($input['image']);
                if ($verify != 'success') {
                    return response()->json(['statusCode' => 422, 'message' => 'Image reject to upload because of ' . $verify . ', Please use another one.'], 200);
                } */
            }
            $update = User::where('id', auth()->user()->id)->update($input);
            if ($update) {
                User::where('id', auth()->user()->id)->where('screen', 1)->update(['screen' => 2]);
                $data = User::find(auth()->user()->id);
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Profile updated successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Profile updating failed.'], 200);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function profileAbout(Request $request)
    {
        try {
            $rules = [
                'about' => 'nullable|min:3|banned_words'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error]);
            }

            $input = $request->only('about');

            $update = User::find(auth()->user()->id)->update($input);
            if ($update) {
                User::where('id', auth()->user()->id)->where('screen', 6)->update(['screen' => 7]);
                $data = User::find(auth()->user()->id);
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Profile about updated successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Profile updating failed.'], 200);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function profileDetail()
    {
        try {
            $data = User::find(auth()->user()->id);
            $data['donation_send'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_by', auth()->user()->id)->sum('amount');
            $data['donation_received'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_to', auth()->user()->id)->sum('amount');
            $data['donation_send_count'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_by', auth()->user()->id)->count();
            $data['donation_received_count'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_to', auth()->user()->id)->count();
            if($data['donation_send'] >= 500 && $data['donation_send'] < 5000) {
                $badge = 'Bronze';
            } elseif($data['donation_send'] >= 5000 && $data['donation_send'] < 50000) {
                $badge = 'Silver';
            } elseif($data['donation_send'] >= 50000 && $data['donation_send'] < 100000) {
                $badge = 'Gold';
            } elseif($data['donation_send'] >= 100000 && $data['donation_send'] < 1000000) {
                $badge = 'Platinum';
            } elseif($data['donation_send'] >= 1000000) {
                $badge = 'Black Diamond';
            } else {
                $badge = '';
            }
            $data['badge'] = $badge;

            $data['amount_for_donate'] = Cashout::with('donation_request')->whereHas('donation_request', function ($q) {
                $q->where('user_id', auth()->user()->id);
            })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
            $data['reel_amount'] = ApiHelper::referral_users();
            // print_r($data); exit;
            if (auth()->user()->subscription_ends_at == NULL || auth()->user()->subscription_ends_at < Carbon::now()) {
                $data['subscription'] = false;
            }else{
                $data['subscription'] = true;
            }
            $data['amount_for_redeem'] = ApiHelper::amount_for_redeem(auth()->user()->id);
            $data = setJsonData($data->toArray());
            return response()->json(['statusCode' => 200, 'message' => 'Profile data found successfully.', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'.$e]);
        }
    }

    public function profileDetails($id)
    {
        try {
            $data = User::find($id);
            if($data){
                $data['donation_send'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_by', $id)->sum('amount');
                $data['donation_received'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_to', $id)->sum('amount');
                $data['donation_send_count'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_by', $id)->count();
                $data['donation_received_count'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_to', $id)->count();
                if($data['donation_send'] >= 500 && $data['donation_send'] < 5000) {
                    $badge = 'Bronze';
                } elseif($data['donation_send'] >= 5000 && $data['donation_send'] < 50000) {
                    $badge = 'Silver';
                } elseif($data['donation_send'] >= 50000 && $data['donation_send'] < 100000) {
                    $badge = 'Gold';
                } elseif($data['donation_send'] >= 100000 && $data['donation_send'] < 1000000) {
                    $badge = 'Platinum';
                } elseif($data['donation_send'] >= 1000000) {
                    $badge = 'Black Diamond';
                } else {
                    $badge = '';
                }
                $data['badge'] = $badge;
                $data['amount_for_donate'] = Cashout::with('donation_request')->whereHas('donation_request', function($q) use ($id) {
                    $q->where('user_id', $id);
                })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
                if ($data->subscription_ends_at == NULL || $data->subscription_ends_at < Carbon::now()) {
                    $data['subscription'] = false;
                }else{
                    $data['subscription'] = true;
                }
                $data['amount_for_redeem'] = ApiHelper::amount_for_redeem($data->id);
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Profile data found successfully.', 'data' => $data]);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Profile data not found.']);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.']);
        }
    }

    public function securityQuestionList()
    {
        try {
            $data = SecurityQuestion::latest()->get();
            $data = setJsonData($data->toArray());
            return response()->json(['statusCode' => 200, 'message' => 'Security questions found successfully.', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.']);
        }
    }

    public function userSecurityQuestionCreate(Request $request)
    {
        try {
            $rules = [
                'security_questions' => 'required|array|min:3',
                'security_questions.*.question_id' => 'required|exists:security_questions,id',
                'security_questions.*.answer' => 'required|string|min:3',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error]);
            }

            $security_questions = $request->only('security_questions');
            $answers = 0;
            foreach($security_questions['security_questions'] as $key => $item) {
                if (isset($item['answer']))
                    $answers++;//return true;
            }
            if($answers < 3) {
                return response()->json(['statusCode' => 422, 'message' => 'Give at least three answers.'], 200);
            }

            //echo json_encode($request->all());die;
            foreach($security_questions['security_questions'] as $key => $sq) {
                $id = [
                    'user_id' => auth()->user()->id,
                    'question_id' => $sq['question_id'],
                ];
                $input = [
                    'user_id' => auth()->user()->id,
                    'question_id' => $sq['question_id'],
                    'answer' => $sq['answer']
                ];
                UserSecurityQuestion::updateOrCreate($id, $input);
            }
            User::where('id', auth()->user()->id)->where('screen', 2)->update(['screen' => 3]);
            $data = userSecurityQuestion::with('question')->where('user_id', auth()->user()->id)->get();
            // $data = setJsonData($data);
            return response()->json(['statusCode' => 200, 'message' => 'Security question updated successfully.', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function userSecurityQuestionList()
    {
        try {
            $data = userSecurityQuestion::with('question')->where('user_id', auth()->user()->id)->get();
            //$data = setJsonData($data);
            return response()->json(['statusCode' => 200, 'message' => 'Profile data found successfully.', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.']);
        }
    }

    public function bankCreate(Request $request)
    {
        try {
            $rules = [
                'bank_name' => 'required|min:3|max:255|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'routing_number' => 'required',
                'account_number' => 'required|numeric|digits_between:9,18'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $input = $request->only('user_id', 'bank_name', 'routing_number', 'account_number');
            $id = [
                'user_id' => auth()->user()->id
            ];
            $insert = BankDetail::updateOrCreate($id, $input);
            if ($insert) {
                User::where('id', auth()->user()->id)->where('screen', 3)->update(['screen' => 4]);
                $data = BankDetail::find($insert->id);
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Bank details updated successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Bank details updation failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function bankDetail()
    {
        try {
            $data = BankDetail::where('user_id', auth()->user()->id)->first();
            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Bank Details found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Bank Details not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function cardCreate(Request $request)
    {
        try {
            $rules = [
                'card_number' => 'required',
                'expiry_date' => 'required',
                'cvv' => 'required|numeric|digits_between:3,6'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $input = $request->only('user_id', 'card_number', 'expiry_date', 'cvv');
            $id = [
                'user_id' => auth()->user()->id
            ];
            $insert = CardDetail::updateOrCreate($id, $input);
            if ($insert) {
                User::where('id', auth()->user()->id)->where('screen', 4)->update(['screen' => 5]);
                $data = CardDetail::find($insert->id);
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Card details updated successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Card details updation failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function cardDetail()
    {
        try {
            $data = CardDetail::where('user_id', auth()->user()->id)->first();
            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Card Details found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Card Details not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function iam(Request $request)
    {
        try {
            $rules = [
                'role' => 'required|in:recipient,donor,unsure,both,corporate',
                'notification' => 'nullable|in:Yes,No'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error]);
            }

            $input = $request->only('role','notification');

            $update = User::find(auth()->user()->id)->update($input);
            if ($update) {
                User::where('id', auth()->user()->id)->where('screen', 5)->update(['screen' => 6]);
                $data = User::find(auth()->user()->id);
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'User role updated successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'User role updating failed.'], 200);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function categories()
    {
        try {
            $data = Category::get();
            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'DoleUpp categories found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'DoleUpp categories not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationRequestCreate(Request $request)
    {
        try {
            $rules = [
                'category_id' => 'required|exists:categories,id',
                'video' => 'required_without:id|mimes:mp4,mov,ogg,qt,flv,avi,wmv',
                'caption' => 'required_without:id|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'Description' => 'nullable|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'donation_amount' => 'required|numeric|min:1|max:'.ApiHelper::referral_users(),
                'is_prime' => 'nullable|in:Yes,No'
            ];

            $messages = [
                'video.required_without' => 'The video is required.',
                'caption.required_without' => 'The caption is required.',
                'donation_amount.required' => 'The DoleUpp amount is required.',
                'donation_amount.numeric' => 'The DoleUpp amount must be a numeric value.',
                'donation_amount.min' => 'The DoleUpp amount must be equal or greater then 1$',
                'donation_amount.max' => (ApiHelper::referral_users() > 0) ? 'The DoleUpp amount must be equal or less then '.ApiHelper::referral_users().setting('currency_symbol') : 'Your DoleUpp request amount limit is over, Please try after completing for 1 year time period.'
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $input = $request->only('category_id', 'caption', 'Description', 'donation_amount', 'is_prime');
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
                    return response()->json(['statusCode' => 422, 'message' => 'Video length should by less then 2 Min.'], 200);
                }

                FFMpeg::fromDisk('videos')
                    ->open($videoFrom[1])
                    ->getFrameFromSeconds(1)
                    ->export()
                    ->toDisk('thumbnail')
                    ->save($imageTo);
                $input['thumbnail'] = $thumbnail;
                $media_id = ApiHelper::videoVerification($videoUrl);
            }
            if(!empty($media_id)) {
                $input['media_id'] = $media_id;
            }
            $input['user_id'] = auth()->user()->id;
            $input['is_prime'] = 'Yes';
            $id = [
                'id' => $request->id
            ];
            $insert = DonationRequest::updateOrCreate($id, $input);
            if ($insert) {
                $data = DonationRequest::with('user', 'category')->withCount('rating_by_me', 'is_reported as is_reported')->withCount(['rating' => function($query) {
                    $query->select(DB::raw('COALESCE(avg(rating),0)'));
                }])->find($insert->id);
                $data = setJsonData($data->toArray());
                if ($insert->wasRecentlyCreated) {
                    $notification = [
                        'title' => 'Upload donation request.',
                        'body' => 'Congratulations you have created a DoleUpp Request. If you have not done it yet SHARE your request to increase your donation 20X.'
                    ];
                    $extraNotificationData = $notification;
                    $extraNotificationData['type'] = 'donation_request';
                    $extraNotificationData['image'] = $insert->thumbnail;
                    $extraNotificationData['id'] = $insert->id;
                    sendNotification(auth()->user()->id, $notification, $extraNotificationData);
                    return response()->json(['statusCode' => 200, 'message' => 'Your DoleUpp request is Submitted and will be posted on app once approved by admin.', 'data' => $data], 200);
                } else {
                    return response()->json(['statusCode' => 200, 'message' => 'Your DoleUpp request is updated successfully.', 'data' => $data], 200);
                }
            }
            return response()->json(['statusCode' => 422, 'message' => 'Your DoleUpp request submitting failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationRequestDelete(Request $request)
    {
        try {
            $rules = [
                'id' => 'required|exists:donation_requests,id'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

//            $data = DonationRequest::withCount(['donors as donation_received' => function($query) {
//                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
//                }])->where('id', $request->id)->first();
            $data = DonationRequest::find($request->id);
            if($data->user_id != auth()->user()->id) {
                return response()->json(['statusCode' => 422, 'message' => 'You are not owner of this DoleUpp.'], 200);
            //} elseif($data->donation_received > 0) {
            //    return response()->json(['statusCode' => 422, 'message' => 'You not delete the DoleUpp Reel after receiving DoleUpp.'], 200);
            } elseif($data->status == 'Approved') {
                return response()->json(['statusCode' => 422, 'message' => 'You not delete the DoleUpp Reel after receiving Approval.'], 200);
            }

            $delete = DonationRequest::where('id', $request->id)->delete();
            if ($delete) {
                return response()->json(['statusCode' => 200, 'message' => 'Your DoleUpp deleted successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Your DoleUpp deleting failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationRequestList(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $offset = 0;
            if ($request->get('page') > 1) {
                $offset = (($request->get('page')-1)*10);
            }

            $columnsToSearch = \Schema::getColumnListing((new DonationRequest)->getTable());
            $searchQuery = '%' . $request->search . '%';
            $data = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'rating_by_me', 'is_reported as is_reported'])
                ->withCount(['donors as donation_received' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                }])
                ->withCount(['rating' => function($query) {
                    $query->select(DB::raw('COALESCE(avg(rating),0)'));
                }])
                ->with('user', 'category')
                ->where('user_id', $request->user_id)

                ->where(function ($q) use($columnsToSearch, $searchQuery) {
                    $q->where('id', 'LIKE', $searchQuery);
                    foreach($columnsToSearch as $column) {
                        $q = $q->orWhere($column, 'LIKE', $searchQuery);
                    }
                });

            $data = $data->latest()->offset($offset)->limit(10)->get();

            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'DoleUpp requests found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'DoleUpp requests not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationRequestDetail($id)
    {
        try {
            $data = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'rating_by_me', 'is_reported as is_reported'])
                ->withCount(['donors as donation_received' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                }])->withCount(['rating' => function($query) {
                    $query->select(DB::raw('COALESCE(avg(rating),0)'));
                }])->with('user', 'category', 'comment.replies.replies.replies.replies.replies')->where('id', $id)->get();
            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'DoleUpp request details found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'DoleUpp request details not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function reasons(Request $request)
    {
        try {
            $rules = [
                'reason_for' => 'nullable|in:reel,user,contact'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $error = (!empty($validator->errors())) ? $validator->errors()->first() : '';
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }
            $reason_for = $request->reason_for ?? 'reel';
            $data = Reason::where('reason_for', $reason_for)->orderByDesc('id')->get();
            if ($data !== null) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Reason list found successfully!', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Reason list not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationRequestReport(Request $request)
    {
        try {
            $rules = [
                'donation_request_id' => 'required',
                'type' => 'required|in:report,unreport',
                'reason_id' => 'required_if:type,report|exists:reasons,id',
                'reason' => 'nullable',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = (!empty($validator->errors())) ? $validator->errors()->first() : '';
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }
            $input = $request->only('donation_request_id');
            $inputs = $request->only('donation_request_id','reason_id','reason');
            $input['user_id'] = auth()->user()->id;
            if($request->type == 'report') {
                $insert = DonationRequestReport::updateOrCreate($input, $inputs);
                if ($insert) {
                    return response()->json(['statusCode' => 200, 'title' => 'Thanks for reporting this reel', 'message' => 'Your feedback is important in helping us keep the DoleUpp community safe.'], 200);
                }
                return response()->json(['statusCode' => 422, 'message' => 'Reporting Failed.'], 200);
            } else {
                $insert = DonationRequestReport::where($input)->delete();
                if ($insert) {
                    return response()->json(['statusCode' => 200, 'message' => 'Unreported Successfully.'], 200);
                }
                return response()->json(['statusCode' => 422, 'message' => 'Un-Reporting Failed.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationDonor($id)
    {
        try {
            $data = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'rating_by_me', 'is_reported as is_reported'])
                ->withCount(['donors as donation_received' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                }])->withCount(['rating' => function($query) {
                    $query->select(DB::raw('COALESCE(avg(rating),0)'));
                }])->with('user', 'category', 'donors', 'donors.donation_by')->where('id', $id)->get();
            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'DoleUpp request details found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'DoleUpp request details not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationList(Request $request)
    {
        try {
            $offset = 0;
            if ($request->get('page') > 1) {
                $offset = (($request->get('page')-1)*10);
            }

            $columnsToSearch = \Schema::getColumnListing((new DonationRequest)->getTable());
            $searchQuery = '%' . $request->search . '%';

            $data = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'rating_by_me', 'is_reported as is_reported'])
                ->withCount(['donors as donation_received' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                }])
                ->withCount(['rating' => function($query) {
                    $query->select(DB::raw('COALESCE(avg(rating),0)'));
                }])
                ->with('user', 'category')->having('donation_received', '<', \DB::raw('donation_amount'))->where('status', 'Approved')
                ->where(function ($q) use($columnsToSearch, $searchQuery) {
                    /*$q->where('id', 'LIKE', $searchQuery);
                    foreach($columnsToSearch as $column) {
                        $q = $q->orWhere($column, 'LIKE', $searchQuery);
                    }*/
                    $q->where('caption', 'LIKE', $searchQuery)->orWhereHas('category', function ($qu) use ($searchQuery){
                        $qu->where('name', 'LIKE', $searchQuery);
                    });
                });
            $data = $data->latest()->orderByDesc('is_prime')->offset($offset)->limit(10)->get();

            /*$data = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'rating_by_me'])
                ->withCount(['donors as donation_received' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                }])->withCount(['rating' => function($query) {
                    $query->select(DB::raw('COALESCE(avg(rating),0)'));
                }])->with('user', 'category')->where('status', 'Approved')->latest()->orderByDesc('is_prime')->offset($offset)->limit(10)->get();*/
            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'DoleUpp videos found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'DoleUpp videos not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
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
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $input = $request->only('donation_request_id', 'comment_type', 'comment', 'parent_id', 'tag_id');

            if($request->comment_type == 'image') {
                // $folderPath = "/storage/images/comment/";
                // // $img_parts = explode(";base64,", $request->comment);
                // // $img_type_aux = explode("image/", $img_parts[0]);
                // // $img_type = $img_type_aux[1];
                // $img_base64 = base64_decode($request->comment);
                // $image = $folderPath . uniqid() . '.gif';//.$img_type;
                // file_put_contents(public_path().$image, $img_base64);
                // $input['comment'] = asset($image);
                $imageName = $request->comment->store('images/comment');
                $input['comment'] = asset('storage/'. $imageName);
                // $gifName = uniqid().'.gif';
                // Image::make($request->file('comment')->getRealPath())->save(public_path('storage/images/comment/').$gifName)->encode('gif');
                // $input['comment'] = asset('storage/images/comment/' . $gifName);
                //$file = $request->file('comment');
                // $extension = $file->getClientOriginalExtension(); // getting image extension
                // $gifName = uniqid().time().'.gif';
                // $file->move('public/storage/images/comment', $gifName);
                // $input['comment'] = asset('storage/images/comment/' . $gifName);
                $verify = ApiHelper::imageVerification($input['comment']);
                if ($verify != 'success') {
                    return response()->json(['statusCode' => 422, 'message' => 'Image reject to upload because of ' . $verify . ', Please use another one.'], 200);
                }
            }

            $input['user_id'] = auth()->user()->id;
            $insert = Comment::create($input);
            if ($insert) {
                $notification = [
                    'title' => 'Received comment.',
                    'body' => @$insert->donation_request->user->name." you have received a message from ".auth()->user()->name." on Doleupp"
                ];
                $extraNotificationData = $notification;
                $extraNotificationData['type'] = 'comment';
                $extraNotificationData['image'] = @$insert->donation_request->thumbnail ?? asset('assets/img/footer-logo.svg');
                $extraNotificationData['id'] = $insert->id;
                $extraNotificationData['donation_request_id'] = $insert->donation_request_id;
                sendNotification($insert->donation_request->user_id, $notification, $extraNotificationData);
                return response()->json(['statusCode' => 200, 'message' => 'Your Comment is Submitted.'], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Your Comment submittion failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationView(Request $request)
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
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $input = $request->only('donation_request_id');
            $input['user_id'] = auth()->user()->id;
            $id = [
                'user_id' => auth()->user()->id,
                'donation_request_id' => $request->donation_request_id
            ];
            $insert = View::updateOrCreate($id ,$input);
            if ($insert) {
                return response()->json(['statusCode' => 200, 'message' => 'Your View is Submitted.'], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Your View submittion failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationShare(Request $request)
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
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $input = $request->only('donation_request_id');
            $input['user_id'] = auth()->user()->id;
            $insert = Share::create($input);
            if ($insert) {
                return response()->json(['statusCode' => 200, 'message' => 'Your Share is Submitted.'], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Your Share submittion failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
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
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            // $input = $request->only('donation_request_id');
            // $input['user_id'] = auth()->user()->id;
            $insert = Wishlist::add($request->donation_request_id, auth()->user()->id);
            if ($insert) {
                return response()->json(['statusCode' => 200, 'message' => 'Added successfully.'], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Added failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
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
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            // $input = $request->only('donation_request_id');
            // $input['user_id'] = auth()->user()->id;
            $insert = WishlistModel::where('item_id', $request->donation_request_id)->where('user_id', auth()->user()->id)->delete();
            if ($insert) {
                return response()->json(['statusCode' => 200, 'message' => 'Removed successfully.'], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Removed failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationWishlistList()
    {
        try {
            // $data = WishlistModel::with(['donation.user', 'donation.category'])->where('user_id', auth()->user()->id)->get();
            $data = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'rating_by_me', 'is_reported as is_reported'])->where('status', 'Approved')
                ->withCount(['donors as donation_received' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                }])->withCount(['rating' => function($query) {
                    $query->select(DB::raw('COALESCE(avg(rating),0)'));
                }])->with(['user', 'category', 'wishlist'])->whereHas('wishlist', function($query){
                    $query->where('user_id', auth()->user()->id);
                })->having('donation_received', '<', \DB::raw('donation_amount'))->get();
            if ($data) {
                $amount_for_donate = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
                    $q->where('user_id', auth()->user()->id);
                })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
                $settings = Setting::find(1);
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Wishlist list found successfully.', 'admin_commission' => $settings->admin_commission, 'amount_for_donate' => $amount_for_donate, 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Wishlist list not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationReadyToPay(Request $request)
    {
        try {
            $rules = [
                'donations' => 'required|array|min:1',
                'donations.*.donation_request_id' => 'required|exists:donation_requests,id',
                'donations.*.amount' => 'required|numeric|min:0',
            ];

            $messages = [
                'donations.*.amount.required' => 'The DoleUpp amount is required.',
                'donations.*.amount.numeric' => 'The DoleUpp amount must be a numeric value.',
                'donations.*.amount.min' => 'The DoleUpp amount must be equal or greater then 0$.'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $data = [];
            foreach ($request->donations as $key => $donation) {
                $find = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'rating_by_me', 'is_reported as is_reported'])->where('status', 'Approved')
                    ->withCount(['donors as donation_received' => function($query) {
                        $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                    }])->withCount(['rating' => function($query) {
                        $query->select(DB::raw('COALESCE(avg(rating),0)'));
                    }])->with(['user', 'category', 'wishlist'])
                    // ->whereHas('wishlist', function($query){
                    //     $query->where('user_id', auth()->user()->id);
                    // })
                    ->find($donation['donation_request_id']);
                if ($find) {
                    $data[$key] = $find;
                    $data[$key]['amount'] = $donation['amount'];
                }
            }

            if ($data) {
                //$data = setJsonData($data);
                $settings=Setting::find(1);
                $amount_for_donate = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
                    $q->where('user_id', auth()->user()->id);
                })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
                return response()->json(['statusCode' => 200, 'message' => 'Ready to pay list found successfully.', 'admin_commission' => $settings->admin_commission, 'amount_for_donate' => $amount_for_donate, 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Ready to pay list not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function amountForDonate()
    {
        try {
            $amount_for_donate = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
                $q->where('user_id', auth()->user()->id);
            })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
            $amount_for_donate = number_format($amount_for_donate, 2,'.','');
            $settings=Setting::find(1);
            if (auth()->user()->subscription_ends_at == NULL || auth()->user()->subscription_ends_at < Carbon::now()) {
                $subscription = false;
            }else{
                $subscription = true;
            }
            $amount_for_redeem = ApiHelper::amount_for_redeem(auth()->user()->id);
            return response()->json(['statusCode' => 200, 'message' => 'Amount for donate found successfully.', 'amount_for_donate' => $amount_for_donate, 'admin_commission' => $settings->admin_commission, 'subscription' => $subscription, 'amount_for_redeem' => $amount_for_redeem], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationMakePayment(Request $request)
    {
        try {
            $rules = [
                'donations' => 'required',
                'use_donation_amount' => 'nullable|in:Yes,No',
                'donations.*.donation_request_id' => 'required|exists:donation_requests,id',
                'donations.*.amount' => 'required|numeric|min:0',
                'video' => 'nullable|mimes:mp4,mov,ogg,qt,flv,avi,wmv',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

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
                    'user_id' => auth()->user()->id,
                ];
            }

            $amount_for_donate = 0;
            if($request->use_donation_amount == 'Yes') {
                $amount_for_donate = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
                    $q->where('user_id', auth()->user()->id);
                })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
            }
            /*$d_amount = 0;
            foreach($request->donations as $d) {
                $d_amount += $d['amount'];
            }*/
            $d_amount = 0;
            $request_donations = is_array($request->donations) ? json_decode(json_encode($request->donations)) : json_decode($request->donations);
            foreach($request_donations as $d) {
                $d_amount += $d->amount;
                $find = DonationRequest::withCount(['donors as donation_received' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                }])->find($d->donation_request_id);
                if($d->amount > ($find['donation_amount'] - $find['donation_received'])){
                    return response()->json(['statusCode' => 422, 'message' => "Your DoleUpp amount is higher than a user requested. Please adjust your donation amount."], 200);
                }
                if ($request->file('video')) {
                    $id = [
                        'donation_request_id' => $d->donation_request_id,
                        'user_id' => auth()->user()->id,
                        'created_at' => Carbon::now()
                    ];
                    $input['donation_request_id'] = $d->donation_request_id;
                    $insert = Feedback::updateOrCreate($id, $input);
                }
            }

            $settings = Setting::find(1);
            $amount_to_pay = 0;
            $commission = $settings->admin_commission ?? 0;
            $d_amount = $d_amount+(($d_amount/100)*$commission);
            if($d_amount > 0 && $d_amount > $amount_for_donate)
            {
                //$amount_to_pay = $d_amount - $amount_for_donate;
                // Cashout::with('donation_request')->whereHas('donation_request', function($q) {
                //     $q->where('user_id', auth()->user()->id);
                // })->whereDate('created_at', '>', Carbon::now()->subDays(7))->update(['fee_for_donation' => 0]);

                $payer = new Payer();
                $payer->setPaymentMethod('paypal');

                $items = [];
                $epayment_id = 'EPAYID-'.strtoupper(Str::random(30));
                $donations = [];
                $request_donations = is_array($request->donations) ? json_decode(json_encode($request->donations)) : json_decode($request->donations);
                foreach ($request_donations as $k => $donation) {
                    $donations[$k] = $donation->donation_request_id;
                    $find = DonationRequest::find($donation->donation_request_id);
                    if ($find) {
                        $donation_amount = $donation->amount+(($donation->amount/100)*$commission);
                        if($donation_amount > 0) {
                            if($amount_for_donate > 0) {
                                if($amount_for_donate >= $donation_amount) {
                                    $fee_for_donation = 0;
                                    $amount_from_wallet = $amount_for_donate;
                                    $admin_commission = $donation_amount-$donation->amount;
                                    $amount_for_donate = $amount_for_donate - $donation_amount;
                                } else {
                                    $fee_for_donation = $donation_amount - $amount_for_donate;
                                    $amount_from_wallet = $amount_for_donate;
                                    $admin_commission = $donation_amount-$donation->amount;
                                    $amount_for_donate = 0;
                                }
                                $price = $fee_for_donation;
                            } else {
                                $price = $donation_amount;
                                $admin_commission = $donation_amount-$donation->amount;
                            }
                            $amount_to_pay += $price;
                            $insert = Donation::create([
                                'payment_id' => $epayment_id,
                                'donation_by' => auth()->user()->id,
                                'donation_to' => $find->user_id,
                                'donation_request_id' => $donation->donation_request_id,
                                'amount' => $donation->amount,
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

                $item_list = new ItemList();
                $item_list->setItems($items);

                $amount = new Amount();
                $amount->setCurrency('USD')
                    ->setTotal($amount_to_pay);

                $transaction = new Transaction();
                $transaction->setAmount($amount)
                    ->setItemList($item_list)
                    ->setDescription('DoleUpp Payment');

                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(URL::route('donate.apistatus'))
                    ->setCancelUrl(URL::route('donate.apistatus'));

                $payment = new Payment();
                $payment->setIntent('Sale')
                    ->setPayer($payer)
                    ->setRedirectUrls($redirect_urls)
                    ->setTransactions(array($transaction));
                try {
                    $payment->create($this->_api_context);
                } catch (\PayPal\Exception\PPConnectionException $ex) {
                    if (\Config::get('app.debug')) {
                        return response()->json(['statusCode' => 422, 'message' => 'Payment link not generate.'], 200);
//                        return redirect()->back()->withError('Connection Timeout');
                    } else {
                        return response()->json(['statusCode' => 422, 'message' => 'Payment link not generate.'], 200);
//                        return redirect()->back()->withError('Some error occur, sorry for inconvenient.');
                    }
                }

                foreach($payment->getLinks() as $link) {
                    if($link->getRel() == 'approval_url') {
                        $redirect_url = $link->getHref();
                        break;
                    }
                }

                if(isset($redirect_url)) {
                    Donation::where('payment_id', $epayment_id)->update(['payment_id' => $payment->getId()]);
                    // DonationPayment::create([
                    //     'donation_request_id' => $insert->id,
                    //     'payment_id' => $payment->getId(),
                    //     'amount' => $settings->prime_donation_price
                    // ]);
                    $stripeUrl=url("api/stripedonation?id=".$insert->id."&paymentId=".$payment->getId()."&paymentFrom=app");
                    $googlepayUrl=url("api/googlepaydonation?id=".$insert->id."&paymentId=".$payment->getId()."&paymentFrom=app");

                    return response()->json(['statusCode' => 200, 'message' => 'Payment link generate successfully.', 'payment_type' => 'online', 'redirect_url' => $redirect_url, 'donations' => implode(",", $donations),"stripeUrl"=>$stripeUrl,"googlepayUrl"=>$googlepayUrl], 200);
//                    return Redirect::away($redirect_url);
                }
                return response()->json(['statusCode' => 422, 'message' => 'Payment link not generate.'], 200);
//                return redirect()->back()->withError('Unknown error occurred');

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
                    $donations = [];
                    $request_donations = is_array($request->donations) ? json_decode(json_encode($request->donations)) : json_decode($request->donations);
                    foreach ($request_donations as $k => $donation) {
                        $donations[$k] = $donation->donation_request_id;
                        $find = DonationRequest::find($donation->donation_request_id);
                        if ($find) {
                            $donation_amount = $donation->amount+(($donation->amount/100)*$commission);
                            if($donation_amount > 0) {
                                Donation::create([
                                    'payment_id' => $epayment_id,
                                    'payment_status' => 'wallet',
                                    'donation_by' => auth()->user()->id,
                                    'donation_to' => $find->user_id,
                                    'donation_request_id' => $donation->donation_request_id,
                                    'amount' => $donation->amount,
                                    'amount_from_wallet' => $donation_amount,
                                    'admin_commission' => $donation_amount-$donation->amount,
                                    'status' => 'earned'
                                ]);
                                WishlistModel::where('item_id', $donation->donation_request_id)->where('user_id', auth()->user()->id)->delete();
                            }
                        }
                    }
                    return response()->json(['statusCode' => 200, 'message' => 'Payment done successfully.', 'payment_type' => 'wallet', 'redirect_url' => '', 'donations' => implode(",", $donations)], 200);
                    //return Redirect::route('donate.payment-status', ['success' => '1', 'donations' => implode(",", $donations)])->withSuccess('Payment success, Thanks for subscription now are you upload your video for donation.');
                } else {
                    return response()->json(['statusCode' => 422, 'message' => 'Total DoleUpp amount must be greater then 0$.'], 200);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
//            return redirect()->back()->withError('Something went wrong.');
        }
    }

    public function donationMakePaymentVideo(Request $request)
    {
        try {
            $rules = [
                //'donations' => 'required',
                'video' => 'required|mimes:mp4,mov,ogg,qt,flv,avi,wmv',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

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
                    'user_id' => auth()->user()->id,
                ];
            }

            /*$donations = explode(',',$request->donations);
            foreach ($donations as $d) {
                if ($request->file('video')) {
                    $id = [
                        'donation_request_id' => $d,
                        'user_id' => auth()->user()->id,
                        'created_at' => Carbon::now()
                    ];
                    $input['donation_request_id'] = $d;
                    $insert = Feedback::updateOrCreate($id, $input);
                }
            }*/
            $input['user_id'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $insert = Feedback::create($input);
            $id=auth()->user()->id;
            $data=[];
            $data['donation_send'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_by', $id)->sum('amount');
            if($data['donation_send'] >= 500 && $data['donation_send'] < 5000) {
                $badge = 'Bronze';
            } elseif($data['donation_send'] >= 5000 && $data['donation_send'] < 50000) {
                $badge = 'Silver';
            } elseif($data['donation_send'] >= 50000 && $data['donation_send'] < 100000) {
                $badge = 'Gold';
            } elseif($data['donation_send'] >= 100000 && $data['donation_send'] < 1000000) {
                $badge = 'Platinum';
            } elseif($data['donation_send'] >= 1000000) {
                $badge = 'Black Diamond';
            } else {
                $badge = '';
            }
            $data['badge'] = $badge;
            return response()->json(['statusCode' => 200, 'message' => 'Video upload successfully.', 'data'=>$data], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
//            return redirect()->back()->withError('Something went wrong.');
        }
    }

//    public function donationMakePayment(Request $request)
//    {
//        try {
//            $rules = [
//                'donations' => 'required',
//                'use_donation_amount' => 'nullable|in:Yes,No',
//                'donations.*.donation_request_id' => 'required|exists:donation_requests,id',
//                'donations.*.amount' => 'required|numeric|min:0',
//                //'video' => 'nullable|mimes:mp4,mov,ogg,qt,flv,avi,wmv',
//            ];
//
//            $validator = Validator::make($request->all(), $rules);
//
//            if ($validator->fails()) {
//                $error = '';
//                if (!empty($validator->errors())) {
//                    $error = $validator->errors()->first();
//                }
//                return response()->json(['statusCode' => 422, 'message' => $error], 200);
//            }
//
//            if ($request->file('video')) {
//                $videoName = $request->video->store('/videos');
//                $videoUrl = url('storage').'/'.$videoName;
//                $videoFrom = explode('/', $videoName);
//                $imageTo = uniqid('reel_experience_', true).time().'.png';
//                $thumbnail = url('storage/videos/thumbnail').'/'.$imageTo;
//
//                FFMpeg::fromDisk('videos')
//                    ->open($videoFrom[1])
//                    ->getFrameFromSeconds(1)
//                    ->export()
//                    ->toDisk('thumbnail')
//                    ->save($imageTo);
//                $input = [
//                    'video' => $videoUrl,
//                    'thumbnail' => $thumbnail,
//                    'user_id' => auth()->user()->id
//                ];
//            }
//
//            $amount_for_donate = 0;
//            if($request->use_donation_amount == 'Yes') {
//                $amount_for_donate = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
//                    $q->where('user_id', auth()->user()->id);
//                })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
//            }
//            /*$d_amount = 0;
//            foreach($request->donations as $d) {
//                $d_amount += $d['amount'];
//            }*/
//            $d_amount = 0;
//            $request_donations = is_array($request->donations) ? json_decode(json_encode($request->donations)) : json_decode($request->donations);
//            foreach($request_donations as $d) {
//                $d_amount += $d->amount;
//                $find = DonationRequest::withCount(['donors as donation_received' => function($query) {
//                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
//                }])->find($d->donation_request_id);
//                if($d->amount > ($find['donation_amount'] - $find['donation_received'])){
//                    return response()->json(['statusCode' => 422, 'message' => "Your DoleUpp amount is higher than a user requested. Please adjust your donation amount."], 200);
//                }
//                if ($request->file('video')) {
//                    $id = [
//                        'donation_request_id' => $d->donation_request_id,
//                        'user_id' => auth()->user()->id,
//                        'created_at' => Carbon::now()
//                    ];
//                    $input['donation_request_id'] = $d->donation_request_id;
//                    $insert = Feedback::updateOrCreate($id, $input);
//                }
//            }
//
//            // $amount_to_pay = 0;
//            if($d_amount > 0 && $d_amount > $amount_for_donate)
//            {
//                $amount_to_pay = $d_amount - $amount_for_donate;
//                // Cashout::with('donation_request')->whereHas('donation_request', function($q) {
//                //     $q->where('user_id', auth()->user()->id);
//                // })->whereDate('created_at', '>', Carbon::now()->subDays(7))->update(['fee_for_donation' => 0]);
//
//                $settings = Setting::find(1);
//                $payer = new Payer();
//                $payer->setPaymentMethod('paypal');
//
//                $items = [];
//                $epayment_id = 'EPAYID-'.strtoupper(Str::random(30));
//                $donations = [];
//                $request_donations = is_array($request->donations) ? json_decode(json_encode($request->donations)) : json_decode($request->donations);
//                foreach ($request_donations as $k => $donation) {
//                    $donations[$k] = $donation->donation_request_id;
//
//                    $find = DonationRequest::find($donation->donation_request_id);
//                    if ($find) {
//
//                        if($donation->amount > 0) {
//                            $insert = Donation::create([
//                                'payment_id' => $epayment_id,
//                                'donation_by' => auth()->user()->id,
//                                'donation_to' => $find->user_id,
//                                'donation_request_id' => $donation->donation_request_id,
//                                'amount' => $donation->amount,
//                                'status' => 'pending'
//                            ]);
//                            $item = new Item();
//                            $item->setName($insert->id)
//                                ->setCurrency('USD')
//                                ->setQuantity(1);
//                            if($amount_for_donate > 0) {
//                                if($amount_for_donate >= $insert->amount) {
//                                    $fee_for_donation = 0;
//                                    $amount_for_donate = $amount_for_donate - $insert->amount;
//                                } else {
//                                    $fee_for_donation = $insert->amount - $amount_for_donate;
//                                    $amount_for_donate = 0;
//                                }
//                                $item->setPrice($amount_for_donate);
//                            } else {
//                                $item->setPrice($insert->amount);
//                            }
//                            $items[] = $item;
//                        }
//                        //WishlistModel::where('item_id', $donation['donation_request_id'])->where('user_id', auth()->user()->id)->delete();
//                    }
//                }
//
//                $item_list = new ItemList();
//                $item_list->setItems($items);
//
//                $amount = new Amount();
//                $amount->setCurrency('USD')
//                    ->setTotal($amount_to_pay);
//
//                $transaction = new Transaction();
//                $transaction->setAmount($amount)
//                    ->setItemList($item_list)
//                    ->setDescription('Donation Payment');
//
//                $redirect_urls = new RedirectUrls();
//                $redirect_urls->setReturnUrl(URL::route('donate.apistatus'))
//                    ->setCancelUrl(URL::route('donate.apistatus'));
//
//                $payment = new Payment();
//                $payment->setIntent('Sale')
//                    ->setPayer($payer)
//                    ->setRedirectUrls($redirect_urls)
//                    ->setTransactions(array($transaction));
//                try {
//                    $payment->create($this->_api_context);
//                } catch (\PayPal\Exception\PPConnectionException $ex) {
//                    if (\Config::get('app.debug')) {
//                        return response()->json(['statusCode' => 422, 'message' => 'Payment link not generate.'], 200);
////                        return redirect()->back()->withError('Connection Timeout');
//                    } else {
//                        return response()->json(['statusCode' => 422, 'message' => 'Payment link not generate.'], 200);
////                        return redirect()->back()->withError('Some error occur, sorry for inconvenient.');
//                    }
//                }
//
//                foreach($payment->getLinks() as $link) {
//                    if($link->getRel() == 'approval_url') {
//                        $redirect_url = $link->getHref();
//                        break;
//                    }
//                }
//
//                if(isset($redirect_url)) {
//                    Donation::where('payment_id', $epayment_id)->update(['payment_id' => $payment->getId()]);
//                    // DonationPayment::create([
//                    //     'donation_request_id' => $insert->id,
//                    //     'payment_id' => $payment->getId(),
//                    //     'amount' => $settings->prime_donation_price
//                    // ]);
//                    $stripeUrl=url("api/stripedonation?id=".$insert->id."&paymentId=".$payment->getId()."&paymentFrom=app");
//                    $googlepayUrl=url("api/googlepaydonation?id=".$insert->id."&paymentId=".$payment->getId()."&paymentFrom=app");
//
//                    return response()->json(['statusCode' => 200, 'message' => 'Payment link generate successfully.', 'payment_type' => 'online', 'redirect_url' => $redirect_url, 'donations' => implode(",", $donations),"stripeUrl"=>$stripeUrl,"googlepayUrl"=>$googlepayUrl], 200);
////                    return Redirect::away($redirect_url);
//                }
//                return response()->json(['statusCode' => 422, 'message' => 'Payment link not generate.'], 200);
////                return redirect()->back()->withError('Unknown error occurred');
//
//            } else {
//                $cashouts = Cashout::with('donation_request')->whereHas('donation_request', function($q) {
//                    $q->where('user_id', auth()->user()->id);
//                })->whereDate('created_at', '>', Carbon::now()->subDays(7))->get();
//                foreach($cashouts as $cashout) {
//                    if($d_amount > 0) {
//                        if($d_amount >= $cashout->fee_for_donation) {
//                            $fee_for_donation = 0;
//                            $d_amount = $d_amount - $cashout->fee_for_donation;
//                        } else {
//                            $fee_for_donation = $cashout->fee_for_donation-$d_amount;
//                            $d_amount = 0;
//                        }
//                        Cashout::where('id', $cashout->id)->update(['fee_for_donation' => $fee_for_donation]);
//                    } else {
//                        break;
//                    }
//                }
//                $epayment_id = 'EPAYID-'.strtoupper(Str::random(30));
//                $donations = [];
//                $request_donations = is_array($request->donations) ? json_decode(json_encode($request->donations)) : json_decode($request->donations);
//                foreach ($request_donations as $k => $donation) {
//                    $donations[$k] = $donation->donation_request_id;
//                    $find = DonationRequest::find($donation->donation_request_id);
//                    if ($find) {
//                        if($donation->amount > 0) {
//                            Donation::create([
//                                'payment_id' => $epayment_id,
//                                'donation_by' => auth()->user()->id,
//                                'donation_to' => $find->user_id,
//                                'donation_request_id' => $donation->donation_request_id,
//                                'amount' => $donation->amount,
//                                'status' => 'earned'
//                            ]);
//                        }
//                        WishlistModel::where('item_id', $donation->donation_request_id)->where('user_id', auth()->user()->id)->delete();
//                    }
//                }
//                return response()->json(['statusCode' => 200, 'message' => 'Payment done successfully.', 'payment_type' => 'wallet', 'redirect_url' => '', 'donations' => implode(",", $donations)], 200);
////                return Redirect::route('donate.payment-status', ['success' => '1', 'donations' => implode(",", $donations)])->withSuccess('Payment success, Thanks for subscription now are you upload your video for donation.');
//
//            }
//        } catch (\Exception $e) {
//            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
////            return redirect()->back()->withError('Something went wrong.');
//        }
//    }

    public function donatePaymentStatus(Request $request)
    {
        try {
            $payment_id = $request->paymentId;

            $cp_pay=LazorDonation::where('payment_id', $payment_id)->first();
            if (empty($request->input('PayerID')) || empty($request->input('token'))) {
                return Redirect::route('subscription.payment-status', ['success' => '0', 'type' => 'donation', 'donations' => '']);
            }elseif($cp_pay){
                $payment = Payment::get($payment_id, $this->_api_context);
                $execution = new PaymentExecution();
                $execution->setPayerId($request->input('PayerID'));
                $result = $payment->execute($execution, $this->_api_context);
                if ($result->getState() == 'approved') {
                    LazorDonation::where('id', $cp_pay->id)->update([
                        'status' => 'success'
                    ]);
                    return Redirect::route('subscription.payment-status', ['success' => '1', 'type' => 'corporation', 'donations' => '']);
                }

                LazorDonation::where('payment_id', $payment_id)->update(['status' => 'failed']);
                return Redirect::route('subscription.payment-status', ['success' => '0', 'type' => 'corporation', 'donations' => '']);
            }

            $d_pay = Donation::where('payment_id', $payment_id)->first();
            if (empty($request->input('PayerID')) || empty($request->input('token')) || !$d_pay) {
                //return Redirect::route('donate.payment-status', ['success' => '0', 'donations' => '']);
                return Redirect::route('subscription.payment-status', ['success' => '0', 'type' => 'donation', 'donations' => '']);
                //return Redirect::route('lazor-reels')->withError('Payment failed, Please try again.');
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
                $items = $result->transactions[0]->item_list->items;
                $donations = [];
                $user_id='';
                foreach($items as $k => $item) {
                    $donation = Donation::find($item->name);
                    $user_id = $donation->donation_by;
                    $donations[$k] = $donation->donation_request_id;
                    Donation::where('id', $item->name)->update(['status' => 'earned']);
                    WishlistModel::where('item_id', $donation->donation_request_id)->where('user_id', $donation->donation_by)->delete();
                    $by=$donation->donation_by_user;
                    $to=$donation->donation_to_user;
                    $notification = [
                        'title' => 'Received donation.',
                        'body' => "Congratulations. ".$to->name." Recipient you have a new Doleupp of $".$donation->amount." from ".$by->name."."
                    ];
                    $extraNotificationData = $notification;
                    $extraNotificationData['type'] = 'donation_received';
                    $extraNotificationData['image'] = $donation->donation_request->thumbnail ?? asset('assets/img/footer-logo.svg');
                    $extraNotificationData['id'] = $donation->id;
                    $extraNotificationData['donation_request_id'] = $donation->donation_request_id;
                    $extraNotificationData['donation_by'] = $donation->donation_by;
                    $extraNotificationData['donation_to'] = $donation->donation_to;
                    sendNotification($donation->donation_to, $notification, $extraNotificationData);
                }
                $d_ids=implode(",", $donations);
                $notification = [
                    'title' => 'Thank you donation.',
                    'body' => "Thank you so much for making a difference in a person's life today. We at DoleUpp appreciate your
                        time and your contribution and we are very thankful that you did SOMETHING. Please invite your
                        friends and family to DoleUpp, so that they can DO SOMETHING GOOD today. Feel free to SHARE."
                ];
                $extraNotificationData = $notification;
                $extraNotificationData['type'] = 'donation_send';
                $extraNotificationData['image'] = asset('assets/img/footer-logo.svg');
                $extraNotificationData['id'] = $d_ids;
                sendNotification($user_id, $notification, $extraNotificationData);
                return Redirect::route('subscription.payment-status', ['success' => '1', 'type' => 'donation', 'donations' => $d_ids])->withSuccess('Payment success, Thanks for subscription now are you upload your video for donation.');
            }
            Donation::where('payment_id', $payment_id)->update(['status' => 'failed']);
            return Redirect::route('subscription.payment-status', ['success' => '0', 'type' => 'donation', 'donations' => '']);
        } catch (\Exception $e) {
            return Redirect::route('subscription.payment-status', ['success' => '0', 'type' => 'donation', 'donations' => '']);
        }
    }

    public function donatePaymentStatusApi(Request $request)
    {
        $type = $request->type ?? 'donation';
        $donations=$request->donations;
        if($request->success == '1') {
            return view('subscription.success', compact('donations','type'));
        }
        return view('subscription.error', compact('donations','type'));
    }

    public function cashoutList(Request $request)
    {
        try {
            $data = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'rating_by_me', 'donation_for_redeem', 'is_reported as is_reported'])->where('status', 'Approved')
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
            }])
            ->withCount(['donors as donation_earned' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'earned');
            }])
            ->withCount(['donors as donation_redeemed' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'redeemed');
            }])
            ->withCount(['rating' => function($query) {
                $query->select(DB::raw('COALESCE(avg(rating),0)'));
            }])->with(['user', 'category', 'donation_for_redeem'])
            ->has('donation_for_redeem', '>', 0)
            ->where('user_id', auth()->user()->id);
            if($request->ids) {
                $ids = explode(',', $request->ids);
                $data = $data->whereIn('id', $ids);
            }
            $data = $data->latest()->get();
            $settings = Setting::select('cash_out_commission','cash_out_fee','cash_out_day','cash_out_note','subscription_price')->find(1);
            $cashout_commission=0.00;
            $cashout_fee=0.00;
            $total_amount=0.00;
            $redeemed_amount=0.00;
            foreach($data as $donation) {
                $commission = ($donation->donation_earned / 100) * $settings->cash_out_commission;
                $fee = ($donation->donation_earned / 100) * $settings->cash_out_fee;
                $t_amount = $donation->donation_earned;
                $r_amount = $donation->donation_earned - ($fee + $commission);
                $cashout_commission+=$commission;
                $cashout_fee+=$fee;
                $total_amount+=$t_amount;
                $redeemed_amount+=$r_amount;
            }
            if (auth()->user()->subscription_ends_at == NULL || auth()->user()->subscription_ends_at < Carbon::now()) {
                $subscription = false;
                $subscription_amount=$settings->subscription_price;
            } else {
                $subscription = true;
                $subscription_amount=0.00;
            }
            $final_amount=$redeemed_amount-$subscription_amount;
            $amounts = [
                'total_amount' => setting('currency_symbol').number_format($total_amount,2,'.',''),
                'cashout_commission' => '-'.setting('currency_symbol').number_format($cashout_commission,2,'.',''),
                'cashout_fee' => '-'.setting('currency_symbol').number_format($cashout_fee,2,'.',''),
                'redeemed_amount' => setting('currency_symbol').number_format($redeemed_amount,2,'.',''),
                'subscription_amount' => '-'.setting('currency_symbol').number_format($subscription_amount,2,'.',''),
                'final_amount' => number_format($final_amount,2,'.',''),
            ];
            $settings = setJsonData($settings->toArray());
            $amounts = setJsonData($amounts);
            $data = setJsonData($data->toArray());
            return response()->json(['statusCode' => 200, 'message' => 'Cashout list found successfully.', 'subscription' => $subscription, 'settings' => $settings, 'amounts' => $amounts, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function donationCashOut(Request $request)
    {
        try {
            $rules = [
                'ids' => 'required',
                //'donations.*.donation_request_id' => 'required|exists:donation_requests,id',
                //'donations.*.amount' => 'required|numeric|min:0',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            /*if(auth()->user()->subscription_ends_at == NULL) {
                return response()->json(['statusCode' => 422, 'message' => 'Please Accept Recipient Subscription for Cashout.'], 200);
            } elseif(auth()->user()->subscription_ends_at < Carbon::now()) {
                return response()->json(['statusCode' => 422, 'message' => 'Your Subscription Expired at '.Carbon::parse(auth()->user()->subscription_ends_at)->format('m/d/Y').', Please Re-new your subscription.'], 200);
            }*/

            $settings = Setting::find(1);
            $donation_req = explode(',', $request->ids);//['90', '91'];
            $cashouts = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'rating_by_me', 'donation_for_redeem', 'is_reported as is_reported'])->where('status', 'Approved')
                ->withCount(['donors as donation_received' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                }])
                ->withCount(['donors as donation_earned' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'earned');
                }])
                ->withCount(['donors as donation_redeemed' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'redeemed');
                }])
                ->withCount(['rating' => function($query) {
                    $query->select(DB::raw('COALESCE(avg(rating),0)'));
                }])->with(['user', 'category', 'donation_for_redeem'])
                ->has('donation_for_redeem', '>', 0)
                ->whereIn('id', $donation_req)
                ->where('user_id', auth()->user()->id)->latest()->get();

            $cashout_commission=0;
            $cashout_fee=0;
            $total_amount=0;
            $redeemed_amount=0;
            foreach($cashouts as $donation) {
                $commission = ($donation->donation_earned / 100) * $settings->cash_out_commission;
                $fee = ($donation->donation_earned / 100) * $settings->cash_out_fee;
                $t_amount = $donation->donation_earned;
                $r_amount = $donation->donation_earned - ($fee + $commission);
                $cashout_commission+=$commission;
                $cashout_fee+=$fee;
                $total_amount+=$t_amount;
                $redeemed_amount+=$r_amount;
            }
            if (auth()->user()->subscription_ends_at == NULL || auth()->user()->subscription_ends_at < Carbon::now()) {
                $subscription = false;
                $subscription_amount=$settings->subscription_price;
            }else{
                $subscription = true;
                $subscription_amount=0;
            }
            $final_amount=$redeemed_amount-$subscription_amount;
            if($final_amount > 0) {
                foreach ($cashouts as $donation) {
                    $cashout_commission = ($donation->donation_earned / 100) * $settings->cash_out_commission;
                    $cashout_fee = ($donation->donation_earned / 100) * $settings->cash_out_fee;
                    $redeemed_amount = $donation->donation_earned - ($cashout_fee + $cashout_commission);
                    if($redeemed_amount <= $subscription_amount) {
                        $subscription_amount-$redeemed_amount;
                        foreach($donation->donation_for_redeem as $dfr) {
                            Donation::where('id', $dfr->id)->update([
                                'status' => 'redeemed'
                            ]);
                        }
                        Cashout::create([
                            'donation_request_id' => $donation->id,
                            'redeemed_amount' => $redeemed_amount,
                            'cash_out_commission' => $cashout_commission,
                            'fee_amount' => $cashout_fee,
                            'fee_for_donation' => $cashout_fee,
                            'status' => 'SUCCESS',
                            'batch_id' => 'BATCHID'.time()
                        ]);
                    } else {
                        if($subscription_amount > 0) {
                            $p_redeemed_amount=$redeemed_amount-$subscription_amount;
                            $subscription_amount=0;
                        } else {
                            $p_redeemed_amount=$redeemed_amount;
                        }
                        $payout = $this->createPayout('Cash out to ' . auth()->user()->name . ' of reel #' . $donation->id, auth()->user()->email, auth()->user()->name, $p_redeemed_amount);
                        if ($payout->getData()->status == true) {
                            foreach ($donation->donation_for_redeem as $dfr) {
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
                            return response()->json(['statusCode' => 422, 'message' => 'Cash out failed, Please try after some time or contact to support team.'], 200);
                        }
                    }
                }
                if($subscription === false) {
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
                    $subscription =  Subscription::create([
                        'name' => 'Yearly Subscription',
                        'user_id' => auth()->user()->id,
                        'payment_id' => 'PAYMENTID-'.time(),
                        'price' => $settings->subscription_price,
                        'quantity' => 1,
                        'starts_from' => $starts_from,
                        'ends_at' => $ends_at,
                        'status' => 'Success'
                    ]);
                    User::where('id', auth()->user()->id)->update(['role' => 'both', 'subscription_ends_at' => $subscription->ends_at]);
                }
                $notification = [
                    'title' => 'Cashout Successfully!',
                    'body' => 'Congratulations! You have withdrawn $'.$final_amount.' amount from your DoleUpp Account. This transfer will reflect in your bank account instantly if you requested an instant otherwise it may take 2-5 business days.'
                ];
                $extraNotificationData = $notification;
                $extraNotificationData['type'] = 'cashout';
                $extraNotificationData['image'] = asset('assets/img/footer-logo.svg');
                sendNotification(auth()->user()->id, $notification, $extraNotificationData);
                return response()->json(['statusCode' => 200, 'message' => 'Cashout successfully.'], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function newsList()
    {
        try {
            $data = News::select('id', 'news_category_id', 'type', 'video', 'thumbnail', 'imgae', 'title', 'slug', 'description as desc', 'created_at', 'updated_at')->with('category')->latest()->get();
            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'News found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'News not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function newsDetail($id)
    {
        try {
            $data = News::select('id', 'news_category_id', 'type', 'video', 'thumbnail', 'imgae', 'title', 'slug', 'description as desc', 'created_at', 'updated_at')->with('category')->find($id);
            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'News found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'News not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function reasonList()
    {
        try {
            $data = Reason::where('status', 'Active')->where('reason_for', 'contact')->orderByDesc('id')->get();
            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Reasons found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Reasons not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function contactCreate(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'company_name' => 'required|min:3|banned_words|email_not_allowed|phone_not_allowed|website_not_allowed',
                'phone' => 'required|numeric|regex:/^([0-9\s\-\+\(\)]*)$/|digits:10',
                'email' => 'required|email',
                'reason_id' => 'required|exists:reasons,id',
                'message' => 'required|min:10|banned_words'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $input = $request->only('name', 'company_name', 'phone', 'email', 'reason_id', 'message');
            $update = Contact::create($input);
            if($update) {
                $data = Contact::find($update->id);
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Contact form submitted successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Contact form submittion failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function makeOnline(Request $request)
    {
        try {
            $input = $request->only('latitude','longitude');
            $input['live_status'] = 'online';
            $input['live_at'] = Carbon::now();
            $update = User::where('id', auth()->user()->id)->update($input);
            if ($update) {
                return response()->json(['statusCode' => 200, 'message' => 'Your live status update successfully.'], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Your live status not update.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function makeOffline()
    {
        try {
            $input = ['live_status' => 'offline'];
            $update = User::where('id', '>', 0)->where('live_at', '<', Carbon::now()->subMinutes(1))->update($input);
            if ($update) {
                return response()->json(['statusCode' => 200, 'message' => 'Live status update successfully.'], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Live status updating failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function notificationUpdate(Request $request)
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
                return response()->json(['statusCode' => 200, 'message' => 'Notification update successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Notification updation failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function notificationList()
    {
        try {
            $data = Notification::where('user_id', auth()->user()->id)->latest()->get();
            if ($data !== null) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Notification list found successfully!', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Notification list not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function settings()
    {
        try {
            $data = Setting::find(1);
            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Setting details found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Setting details not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function myDonor(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $offset = 0;
            if ($request->get('page') > 1) {
                $offset = (($request->get('page')-1)*10);
            }
            $data = Donation::with('donation_by', 'donation_request.user', 'donation_request.category')
                ->where('donation_to', $request->user_id)->latest()->offset($offset)->limit(10)->get();
            if($data) {
                $array = $data->toArray();
                foreach($array as $key => $value)
                {
                    $data[$key]['donation'] = $value['donation_by'];
                    unset($data[$key]['donation_by']);
                }
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'DoleUpp videos found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'DoleUpp videos not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function myDonation(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required|exists:users,id',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $offset = 0;
            if ($request->get('page') > 1) {
                $offset = (($request->get('page')-1)*10);
            }
            $data = Donation::with('donation_to', 'donation_request.user', 'donation_request.category')
                ->where('donation_by', $request->user_id)->latest()->offset($offset)->limit(10)->get();
            if($data) {
                $array = $data->toArray();
                foreach($array as $key => $value)
                {
                    $data[$key]['donation'] = $value['donation_to'];
                    unset($data[$key]['donation_to']);
                }
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'DoleUpp videos found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'DoleUpp videos not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function faqs()
    {
        try {
            $data = Faq::latest()->get();
            if ($data !== null) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Faq found successfully!', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Faq not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function banks()
    {
        try {
            $data = Bank::get();
            if ($data !== null) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Banks found successfully!', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Banks not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function cities()
    {
        try {
            $data = City::get();
            if ($data !== null) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Cities found successfully!', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Cities not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function rateToReel(Request $request){
        try {
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
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }
            $input = $request->only('rating');
            $input['user_id'] = auth()->user()->id;
            $insert = Rating::create($input);

            /*$donations = explode(',', $request->donation_request_id);
            foreach ($donations as $kay => $donation) {
                $id = [
                    'donation_request_id' => $donation,
                    'user_id' => auth()->user()->id
                ];
                $input = $request->only('rating');
                $input['donation_request_id'] = $donation;
                $input['user_id'] = auth()->user()->id;
                $insert = Rating::updateOrCreate($id, $input);
            }*/
            if ($insert) {
                $id=auth()->user()->id;
                $data=[];
                $data['donation_send'] = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_by', $id)->sum('amount');
                if($data['donation_send'] >= 500 && $data['donation_send'] < 5000) {
                    $badge = 'Bronze';
                } elseif($data['donation_send'] >= 5000 && $data['donation_send'] < 50000) {
                    $badge = 'Silver';
                } elseif($data['donation_send'] >= 50000 && $data['donation_send'] < 100000) {
                    $badge = 'Gold';
                } elseif($data['donation_send'] >= 100000 && $data['donation_send'] < 1000000) {
                    $badge = 'Platinum';
                } elseif($data['donation_send'] >= 1000000) {
                    $badge = 'Black Diamond';
                } else {
                    $badge = '';
                }
                $data['badge'] = $badge;
                return response()->json(['statusCode' => 200, 'message' => 'Your Rating is Submitted.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Your Rating submitting failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function getMyRating(Request $request){
        try {
            $rules = [
                'donation_request_id' => 'required|exists:donation_requests,id',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $find = Rating::where('donation_request_id', $request->donation_request_id)->where('user_id', auth()->user()->id);

            if ($find) {
                return response()->json(['statusCode' => 200, 'message' => 'Your Rating found successfully.', 'rating' => $find->rating], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Your are not rate to this reel.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
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
        $redirect_urls->setReturnUrl(URL::route('subscription.apistatus'))
            ->setCancelUrl(URL::route('subscription.apistatus'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                return response()->json(['statusCode' => 422, 'message' => 'Payment link not generate.'], 200);
//              return Redirect::route('subscription')->withError("Connection Timeout.");
            } else {
                return response()->json(['statusCode' => 422, 'message' => 'Payment link not generate.'], 200);
//                return Redirect::route('subscription')->withError('Some error occur, sorry for inconvenient');
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return response()->json(['statusCode' => 422, 'message' => 'Payment link not generate.'], 200);
//            return Redirect::route('subscription')->withError("Something went wrong, Please try after some time.");
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

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
          $subcription=  Subscription::create([
                'name' => 'Yearly Subscription',
                'user_id' => auth()->user()->id,
                'payment_id' => $payment->getId(),
                'price' => $settings->subscription_price,
                'quantity' => 1,
                'starts_from' => $starts_from,
                'ends_at' => $ends_at,
                'status' => 'Pending'
            ]);
            $stripeUrl=url("api/stripe?id=".$subcription->id."&paymentFrom=app");
            $googlepayUrl=url("api/googlepay?id=".$subcription->id."&paymentFrom=app");
            return response()->json(['statusCode' => 200, 'message' => 'Payment link generate successfully.', 'redirect_url' => $redirect_url,"stripeUrl"=>$stripeUrl,"googlepayUrl"=>$googlepayUrl], 200);
        }
        return response()->json(['statusCode' => 422, 'message' => 'Payment link not generate.'], 200);
    }

    public function subscriptionPaymentStatus(Request $request)
    {
        $payment_id = $request->paymentId;
        $subscription = Subscription::where('payment_id', $payment_id)->first();

        if (empty($request->input('PayerID')) || empty($request->input('token')) || !$subscription) {
            Subscription::where('payment_id', $payment_id)->update(['status' => 'Failed']);
            return Redirect::route('subscription.payment-status', ['success' => '0', 'type' => 'subscription', 'donations' => ''])->withError('Payment failed, Please try again.');
            // return Redirect::route('subscription')->withError('Payment failed, Please try again.');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
            Subscription::where('id', $subscription->id)->update(['status' => 'Success']);
            User::where('id', $subscription->user_id)->update(['role' => 'both', 'subscription_ends_at' => $subscription->ends_at]);
            return Redirect::route('subscription.payment-status', ['success' => '1', 'type' => 'subscription', 'donations' => ''])->withSuccess('Payment success, Thanks for subscription now are you upload your video for donation.');
        }

        Subscription::where('id', $subscription->id)->update(['status' => 'Failed']);
        return Redirect::route('subscription.payment-status', ['success' => '0', 'type' => 'subscription', 'donations' => ''])->withError('Payment failed, Please try again.');
    }

    public function showSubscriptionPaymentStatus(Request $request)
    {
        $type = $request->type ?? 'donation';
        $donations=$request->donations;
        /*if($type == 'corporation') {
            if($request->success == '1') {
                return view('subscription.corporate-success');
            }
            return view('subscription.corporate-failed');
        } else {*/
            if($request->success == '1') {
                return view('subscription.success',compact('donations','type'));
            }
            return view('subscription.error',compact('donations','type'));
        /*}*/
    }

    public function usersList(Request $request)
    {
        try {
            $offset = 0;
            if ($request->get('page') > 1) {
                $offset = (($request->get('page')-1)*50);
            }

            $columnsToSearch = \Schema::getColumnListing((new DonationRequest)->getTable());
            $searchQuery = '%' . $request->search . '%';
            $ids=['1','2',auth()->user()->id];
            //$data = User::select('id', 'referral_code', 'name', 'email')
            $data = User::select('*')
                ->whereNotNull('email')
                ->whereNotIn('id', $ids)
                ->where(function ($q) use($columnsToSearch, $searchQuery) {
                    $q->where('id', 'LIKE', $searchQuery)
                        ->orWhere('referral_code', 'LIKE', $searchQuery)
                        ->orWhere('name', 'LIKE', $searchQuery);
                })->orderBy('name')->offset($offset)->limit(10)->get();

            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Users found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Users not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function corporateDonationPost(Request $request)
    {
        try {
            $rules = [
                'categories' => 'required|array|min:1',
                'name' => 'required',
                'donation_amount' => 'required|numeric|min:50',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

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
            $redirect_urls->setReturnUrl(URL::route('donate.apistatus'))
                ->setCancelUrl(URL::route('donate.apistatus'));

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
                $stripeUrl=url("api/stripedonation?id=".$insert->id."&paymentId=".$payment->getId()."&paymentFrom=app");
                $googlepayUrl=url("api/googlepaydonation?id=".$insert->id."&paymentId=".$payment->getId()."&paymentFrom=app");

                return response()->json(['statusCode' => 200, 'message' => 'Payment link generate successfully.', 'payment_type' => 'online', 'redirect_url' => $redirect_url, "stripeUrl"=>$stripeUrl,"googlepayUrl"=>$googlepayUrl], 200);
//              return Redirect::away($redirect_url);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Payment link not generated.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function corporateDonationList(){
        try {
            $donations = LazorDonation::where('user_id', auth()->user()->id)->latest()->get();
            return response()->json(['statusCode' => 200, 'message' => 'Corporate DoleUpp list found successfully.', 'data' => $donations], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function corporateDonationDetail(Request $request){
        try {
            $rules = [
                'id' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }
            $id=$request->id;
            $lazor_donations = LazorDonation::find($id);
            if ($lazor_donations) {
                $donations = Donation::with('donation_by', 'donation_request.user', 'donation_request.category')
                ->where('lazor_donation_id', $id)->latest()->get();
                return response()->json(['statusCode' => 200, 'message' => 'Corporate DoleUpp detail found successfully.', 'data' => $donations], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Corporate DoleUpp detail not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function makeIosSubscription(Request $request)
    {
        try {
            $rules = [
                'payment_id' => 'required',
                'price' => 'required|numeric',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }
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
            $subscription = Subscription::create([
                'name' => 'Yearly Subscription',
                'user_id' => auth()->user()->id,
                'payment_id' => $request->payment_id,
                'price' => $request->price ?? 0,
                'quantity' => 1,
                'starts_from' => $starts_from,
                'ends_at' => $ends_at,
                'status' => 'Success'
            ]);
            if($subscription) {
                User::where('id', $subscription->user_id)->update(['role' => 'both', 'subscription_ends_at' => $ends_at]);
            }
            return response()->json(['statusCode' => 200, 'message' => 'Subscription Successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Subscription Failed.'], 200);
        }
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

    public function myWallet()
    {
        try {
            $user_id = auth()->user()->id;
            $donation_send = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_by', $user_id)->sum('amount');
            $donation_received = Donation::whereIn('status', ['earned', 'redeemed'])->where('donation_to', $user_id)->sum('amount');
            $cashout_total = Donation::whereIn('status', ['redeemed'])->where('donation_to', auth()->user()->id)->sum('amount');
            $cashout = Cashout::query()//with('donation_request')
                ->whereHas('donation_request', function($q) use ($user_id){
                    $q->where('user_id', $user_id);
                });
            $cashouts = $cashout->latest()->get();
            $cashout_received = $cashout->sum('redeemed_amount');
            $cashout_commission = $cashout->sum('cash_out_commission');
            $cashout_fee = $cashout->sum('fee_amount');
            $amount_for_donate = $cashout->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
            $amount_for_redeem = ApiHelper::amount_for_redeem(auth()->user()->id);
            $amounts = [
                'donation_send' => setting('currency_symbol').number_format($donation_send, 2,'.',''),
                'donation_received' => setting('currency_symbol').number_format($donation_received, 2,'.',''),
                'cashout_total' => setting('currency_symbol').number_format($cashout_total, 2,'.',''),
                'cashout_received' => setting('currency_symbol').number_format($cashout_received, 2,'.',''),
                'cashout_commission' => setting('currency_symbol').number_format($cashout_commission, 2,'.',''),
                'cashout_fee' => setting('currency_symbol').number_format($cashout_fee, 2,'.',''),
                'amount_for_donate' => setting('currency_symbol').number_format($amount_for_donate, 2,'.',''),
                'amount_for_redeem' => setting('currency_symbol').number_format($amount_for_redeem, 2,'.',''),
            ];
            return response()->json(['statusCode' => 200, 'message' => 'Wallet found successfully.', 'amounts' => $amounts, 'data' => $cashouts], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function rolesList()
    {
        try {
            $data = Role::get();
            if($data) {
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Roles found successfully.', 'data' => $data], 200);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Roles not found.'], 200);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.'], 200);
        }
    }

    public function updateSetting(Request $request)
    {
        try {
            $rules = [
                'type' => 'required|in:email,phone',
                'email' => 'required_if:type,email|email|unique:users,email,'.auth()->user()->id,
                'country_code' => 'required_if:type,phone',
                'phone' => 'required_if:type,phone|unique:users,phone,'.auth()->user()->id,
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['success' => false, 'message' => $error], 200);
            }

            if($request->type == 'email') {
                //if (auth()->user()->email == $request->email) {
                    $otp = 1122;//rand(1000, 9999);
                    $input = $request->only('email');
                    $input['type']=$request->type;
                    $otp = 1122;//md5(rand(1000, 9999));
                    $input['otp'] = md5($otp);
                    $input['token'] = Str::random(40).'.'.time();
                    $data = UpdateSetting::create($input);
                    $data['otp_text']=$otp;
                    return response()->json(['statusCode' => 200, 'message' => 'Otp successfully send on phone no.', 'data' => $data], 200);
                //}
                //return response()->json(['statusCode' => 422, 'message' => 'Invalid email.'], 200);
            } else {
                //if (auth()->user()->country_code == $request->country_code && auth()->user()->phone == $request->phone) {
                    $otp = 1122;//rand(1000, 9999);
                    $input = $request->only('country_code', 'phone');
                    $input['type']=$request->type;
                    $otp = 1122;//md5(rand(1000, 9999));
                    $input['otp'] = md5($otp);
                    $input['token'] = Str::random(40).'.'.time();
                    $data = UpdateSetting::create($input);
                    $data['otp_text']=$otp;

                    $to = auth()->user();
                    $details = [
                        'to' => $to,
                        'subject' => 'Otp for updating phone number',
                        'body' => 'You phone number updating otp is '.$otp
                    ];
                    $sent = \Mail::to($to->email, $to->name)->send(new CommonMail($details));
                    // return $sent ? true : false;
                    return response()->json(['statusCode' => 200, 'message' => 'Otp successfully send on email.', 'data' => $data], 200);
                //}
                //return response()->json(['statusCode' => 422, 'message' => 'Invalid phone no.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => $e->getMessage()], 200);
        }
    }

    public function updateSettingVerify(Request $request)
    {
        try {
            $rules = [
                'type' => 'required|in:email,phone',
                'token' => 'required',
                'otp' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }
            if($request->type == 'email') {
                $find = UpdateSetting::where('type', $request->type)->where('token', $request->token)->where('otp', md5($request->otp))->first();
                if ($find) {
                    $input = [
                        'email' => $find->email
                    ];
                    User::where('id',auth()->user()->id)->update($input);
                    return response()->json(['statusCode' => 200, 'message' => 'Email update successfully.']);
                }
                return response()->json(['statusCode' => 422, 'message' => 'Email updating failed.']);
            } else {
                $find = UpdateSetting::where('type', $request->type)->where('token', $request->token)->where('otp', md5($request->otp))->first();
                if ($find) {
                    $input = [
                        'country_code' => $find->country_code,
                        'phone' => $find->phone
                    ];
                    User::where('id', auth()->user()->id)->update($input);
                    return response()->json(['statusCode' => 200, 'message' => 'Phone no. update successfully.']);
                }
                return response()->json(['statusCode' => 422, 'message' => 'Phone no. updating failed.']);
            }
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => $e->getMessage()]);
        }
    }
}
