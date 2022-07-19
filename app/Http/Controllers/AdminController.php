<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Category;
use App\Models\DonationRequestReport;
use App\Models\Feedback;
use App\Models\Notification;
use App\Models\Rating;
use App\Models\Subscription;
use Haruncpi\LaravelUserActivity\Models\Log;
use Illuminate\Support\Facades\Http;
use \Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\DonationRequest;
use App\Models\User;
use App\Models\Faq;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\View;
use App\Models\Share;
use App\Models\Wishlist;
use App\Models\Comment;
use App\Models\Pages;
use DB;
use App\Mail\AuthMail;
use App\Models\Cashout;
use App\Models\LazorDonation;
use VideoThumbnail;
use Validator;
use Exception;
use FFMpeg;
use Image;
use GuzzleHttp\Client;
use DataTables;
use Carbon\Carbon;

class AdminController extends Controller
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


    public function is_admin(){
        $is_admin = User::where('is_admin',1)->first();
        if (!empty($is_admin)) {
            return true;
        }else{
            return false;
        }
    }

    public function is_subadmin(){
        $is_subadmin = User::where('is_admin',2)->first();
        if (!empty($is_subadmin)) {
            return true;
        }else{
            return false;
        }
    }

    public function dashboard(){
        $total_donations = DonationRequest::all()->count();
        $prime_donations = DonationRequest::where('is_prime',"Yes")->get()->count();
        $total_users = User::all()->count();
        $active_users = User::where('status',1)->count();
        $block_users = User::where('status',0)->count();
        $contact = Contact::all()->count();
        $news = News::all()->count();
        return view('admin.dashboard',compact('total_donations','total_users','contact','active_users','block_users','contact','news','prime_donations'));
    }

    public function usersList(){
        // if (!$this->is_admin()) {
        //     return redirect('admin/dashboard')->with('error',"You are not allowed!");
        // }
        $users = User::where('is_admin','!=',1)->where('email','!=',NULL)->get();
        foreach ($users as $key => $value) {
            $value->donation_send = Donation::where('donation_by', $value->id)->sum('amount');
            $value->donation_received = Donation::where('donation_to', $value->id)->sum('amount');
            if($value->donation_send >= 1500 && $value->donation_send < 5000) {
                $badge = 'Bronze';
                $color_code = '#CD7F32';
            } elseif($value->donation_send >= 5000 && $value->donation_send < 10000) {
                $badge = 'Silver';
                $color_code = '#C0C0C0';
            } elseif($value->donation_send >= 5000 && $value->donation_send < 10000) {
                $badge = 'Gold';
                $color_code = '#FFD700';
            } elseif($value->donation_send >= 15000 && $value->donation_send < 100000) {
                $badge = 'Platinum';
                $color_code = '';
            } elseif($value->donation_send >= 100000) {
                $badge = 'Black Diamond';
                $color_code = '#928b8b';
            } else {
                $badge = '-';
                $color_code = '';
            }
            $value->badge = $badge;
            $value->color_code = $color_code;
        }
        return view('admin.users-list', compact('users'));
    }

    public function usersEdit($id){
        // if (!$this->is_admin()) {
        //     return redirect('admin/dashboard')->with('error',"You are not allowed!");
        // }
        $users = User::where('id',$id)->first();
        return view('admin.users-edit', compact('users'));
    }

    public function usersDetailsUpdate(Request $request, $id){
        // if (!$this->is_admin()) {
        //     return redirect('admin/dashboard')->with('error',"You are not allowed!");
        // }
        $request->validate([
            'name' => 'required',
            'dob' => 'required',
            'university' => 'required',
            'occupation' => 'required',
            'state' => 'required',
            'country' => 'required',
            'image' => 'mimes:jpg,png,jpeg'
        ]);

        /*$input = $request->only( 'name', 'dob', 'university', 'occupation', 'about', 'address', 'status', 'role', );
        if (!empty($request->image)) {
            $imageName = $request->image->store('images/profile');
            $input['image'] = asset('storage/' . $imageName);
        }
        $update = User::where('id', $id)->update($input);*/

        $user = User::find($id);
        $user->name = $request->name;
        $user->dob = $request->dob;
        $user->university = $request->university;
        $user->occupation = $request->occupation;
        $user->about = $request->about;
        $user->address = $request->address;
        $user->state = $request->state;
        $user->country = $request->country;
        $user->status = $request->status;
        $user->role = $request->role;
        if (!empty($request->image)) {
            $imageName = $request->image->store('images/profile');
            $user->image = asset('storage/' . $imageName);
        }
        $user->save();

        if($user) {
            return redirect()->route('users-list')->with('info', "User Updated Successfully.");
        }else{
            return redirect()->route('users-list')->with('error', "Error! Please Try Again.");
        }
    }

    public function usersDetailList($id){
        // if (!$this->is_admin()) {
        //     return redirect('admin/dashboard')->with('error',"You are not allowed!");
        // }
        $user_details = User::where('id',$id)->with('bank_details','card_details')->first();
        if($user_details) {
            $security_questions = User::where('id', $id)->with('security_questions')->get();
            $wishlist = Wishlist::where('user_id', $id)->get();
            $donation_to = Donation::where('donation_by', $id)->latest()->get();
            $donation_from = Donation::where('donation_to', $id)->latest()->get();
            $donations = DonationRequest::where('user_id', $id)->withCount(['donors as donation_received' => function ($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'));
            }])->get();
            foreach ($donations as $key => $value) {
                $views = View::where('donation_request_id', $value->id)->get();
                $share = Share::where('donation_request_id', $value->id)->get();
                $value->total_views = count($views);
                $value->total_share = count($share);
            }
            $amount_for_donate = Cashout::with('donation_request')
                ->whereHas('donation_request', function($q) use($id) {
                    $q->where('user_id', $id);
                })->whereDate('created_at', '>', Carbon::now()->subDays(7))->sum('fee_for_donation');
            $amount_for_donate = number_format($amount_for_donate, 2);
            $amount_for_redeem = ApiHelper::amount_for_redeem($id);
            return view('admin.user-details', compact('user_details', 'amount_for_donate', 'amount_for_redeem', 'security_questions', 'wishlist', 'donation_to', 'donation_from', 'donations'));
        }
        abort(404);
    }

    public function usersDelete($id){
        // if (!$this->is_admin()) {
        //     return redirect('admin/dashboard')->with('error',"You are not allowed!");
        // }
        $user = User::find($id);
        if($user->delete()) {
            return redirect()->route('users-list')->with('info', "User Deleted Successfully.");
        }else{
            return redirect()->route('users-list')->with('error', "Error! Please Try Again.");
        }
    }

    public function usersStatusUpdate(Request $request, $status, $id){
        $user = User::where('id', $id)->first();
        if ($status == "active") {
            $user->status = 1;
        }else{
            $user->status = 0;
        }
        $user->save();
        return redirect()->route('users-list')->with('info', "Status Updated Successfully.");
    }

    public function donationList(Request $request){
        if($request->ajax()) {
            $data = DonationRequest::with(['user', 'category', 'donation_for_redeem'])
                ->withCount(['views', 'comments', 'shares', 'wishlist', 'donation_for_redeem', 'reports'])
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
                }])->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function ($query) {
                    return '<a href="'.url('/admin/user-details', $query->user['id']).'"><font color="blue">'.$query->user['name'].'</font></a>';
                })
                ->addColumn('category', function ($query) {
                    return $query->category->name;
                })
                ->editColumn('is_prime', function ($query) {
                    return ($query->is_prime == 'Yes') ? '<font color="green">'.$query->is_prime.'</font>' : '<font color="red">'.$query->is_prime.'</font>';
                })
                ->editColumn('video', function ($query) {
                    return '<a href="'.$query->video.'" class="ply-btn"><img height="80" src="'.$query->thumbnail.'"></a>';
                })
                ->editColumn('reject_prob', function ($query) {
                    return ($query->reject_prob) ? (floatval($query->reject_prob)*100).'%' : '';
                })
                ->editColumn('created_at', function ($query) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $query->created_at)->format('m-d-Y');
                })
                ->editColumn('donation_amount', function ($query) {
                    return '$'.number_format($query->donation_amount, 2);
                })
                ->editColumn('donation_received', function ($query) {
                    return '$'.number_format($query->donation_received, 2);
                })
                ->editColumn('donation_redeemed', function ($query) {
                    return '$'.number_format($query->donation_redeemed, 2);
                })
                ->editColumn('donation_earned', function ($query) {
                    return '$'.number_format($query->donation_earned, 2);
                })
                ->addColumn('rating', function ($query) {
                    return number_format($query->rating_count, 1);
                })
                ->editColumn('status', function ($query) {
                    return '<div class="btn-group dropright">
                        <button type="button"
                                class="btn btn-dark btn-sm">'.$query->status.'</button>
                        <button type="button" title="View Status"
                                class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split"
                                id="dropdownMenuReference3" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" data-reference="parent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-chevron-down">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuReference3">
                            <a class="dropdown-item"
                               href="'.url('/admin/donation-status-update/Approved', $query->id).'">Approved</a>
                            <a class="dropdown-item"
                               href="'.url('/admin/donation-status-update/Rejected', $query->id).'">Rejected</a>
                        </div>
                    </div>';
                })
                ->addColumn('action_btn', function($query){
                    $action_btn = '';
                    if(auth()->user()->is_admin == 1) {
                        $action_btn.= '<a href="' . url('/admin/donation-delete', $query->id) . '"
                            onclick="return confirm("Are you sure you want to delete this?")"><span
                            class="badge outline-badge-danger"> Delete </span></a>';
                    }
                    $action_btn.= '<a href="'.url('/admin/donors', $query->id).'"><span
                                    class="badge outline-badge-primary"> View Donors</span></a>
                            <a href="'.url('/admin/cashouts', $query->id).'"><span
                                    class="badge outline-badge-success"> View Cashout</span></a>';
                    return $action_btn;
                })
                ->rawColumns(['user', 'is_prime', 'video', 'reject_prob', 'created_at', 'status', 'action_btn',])
                ->make(true);
        }
        /*$donations = DonationRequest::withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'));
            }])->withCount(['donors as donation_earned' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'earned');
            }])
            ->withCount(['donors as donation_redeemed' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'redeemed');
            }])->latest()->get();
        foreach ($donations as $key => $value) {
            $views = View::where('donation_request_id',$value->id)->get();
            $share = Share::where('donation_request_id',$value->id)->get();
            $value->total_views = count($views);
            $value->total_share = count($share);
        }*/

        return view('admin.donations-list');
    }

    public function donationStatusUpdate(Request $request, $status, $id){
        $donations = DonationRequest::where('id', $id)->first();
        $donations->status = $status;
        $donations->save();
        return redirect()->route('donations-list')->with('info', "Status Updated Successfully.");
    }

    public function donorsList($id){
        $donors = Donation::where('donation_request_id', $id)->get();
        return view('admin.donors-list', compact('donors'));
    }

    public function lazorDonationsList(){
        $donations = LazorDonation::latest()->get();
        return view('admin.lazor-donations', compact('donations'));
    }

    public function lazorDonationsDetail($id){
        $lazor_donations = LazorDonation::find($id);
        if($lazor_donations) {
            $donations = Donation::where('lazor_donation_id', $id)->latest()->get();
            return view('admin.lazor-donations-detail', compact('lazor_donations', 'donations'));
        }
        abort(404);
    }

    public function donationToReels($id){
        $lazor_donations = LazorDonation::find($id);
        if($lazor_donations) {
            $categories = Category::get();
            $seleted_categories = Category::whereIn('id', explode(',',$lazor_donations->categories))->get();
            $donations = [];
            return view('admin.lazor-donations-to-reels', compact('lazor_donations', 'categories', 'seleted_categories', 'donations'));
        }
        abort(404);
    }

    public function donationToReelsPost($id, Request $request)
    {
        try {
            $request->validate([
                'reel' => 'required|exists:donation_requests,id',
                'amount' => 'required|numeric|min:1'
            ]);

            $find = LazorDonation::find($id);
            $donation = DonationRequest::withCount(['donors as donation_received' => function($query) {
                    $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
                }])->find($request->reel);
            $pending_amount = $donation->donation_amount-$donation->donation_received;
            if($find->amount_for_donate >= $request->amount && $pending_amount >= $request->amount) {
                $donation_amount = $find->amount_for_donate - $request->amount;
                $insert = Donation::create([
                    'payment_id' => $find->payment_id,
                    'donation_by' => $find->user_id,
                    'donation_to' => $donation->user_id,
                    'donation_request_id' => $donation->id,
                    'amount' => $request->amount ?? 0,
                    'amount_from_wallet' => 0,
                    'admin_commission' => 0,
                    'status' => 'earned',
                    'lazor_donation_id' => $find->id,
                    'paid_by' => 'admin'
                ]);
                if($insert) {
                    LazorDonation::where('id', $id)->update(['amount_for_donate' => $donation_amount,]);
                    return redirect()->to(route('cd.list'))->withSuccess('Donation done successfully.');
                }
                return redirect()->back()->withError('Something went wrong.');
            }
            return redirect()->back()->withError('The invalid amount.');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function reelSearch(Request $request)
    {
        $limit = 30;
        $offset = 0;
        if ($request->get('page') > 1) {
            $offset = (($request->get('page')-1)*$limit);
        }
        $searchQuery = '%'.$request->q.'%';
        $result = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'rating_by_me'])->where('status', 'Approved')
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
            }])->withCount(['donors as donation_earned' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'earned');
            }])->withCount(['donors as donation_redeemed' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'redeemed');
            }])->withCount(['rating' => function($query) {
                $query->select(DB::raw('COALESCE(avg(rating),0)'));
            }])->with(['user', 'category', 'donation_for_redeem'])
            ->having('donation_received', '<', \DB::raw('donation_amount'))
            ->where(function ($query) use ($searchQuery){
                $query->where('caption', 'LIKE', $searchQuery)
                    ->orWhereHas('user', function ($q) use($searchQuery){
                        $q->where('name', 'LIKE', $searchQuery);
                    })->orWhereHas('category', function ($q) use($searchQuery){
                        $q->where('name', 'LIKE', $searchQuery);
                    });
            })->offset($offset)->limit($limit)->get();
        $count = DonationRequest::withCount(['views', 'comments', 'shares', 'wishlist', 'rating_by_me'])->where('status', 'Approved')
            ->withCount(['donors as donation_received' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->whereIn('status', ['earned', 'redeemed']);
            }])->withCount(['donors as donation_earned' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'earned');
            }])->withCount(['donors as donation_redeemed' => function($query) {
                $query->select(DB::raw('COALESCE(sum(amount),0)'))->where('status', 'redeemed');
            }])->withCount(['rating' => function($query) {
                $query->select(DB::raw('COALESCE(avg(rating),0)'));
            }])->with(['user', 'category', 'donation_for_redeem'])
            ->having('donation_received', '<', \DB::raw('donation_amount'))
            ->where(function ($q) use ($searchQuery){
                $q->where('caption', 'LIKE', $searchQuery);
            })->count();
        return response()->json(["incomplete_results" => false, "items" => $result, "total_count" => $count]);
    }

    public function categorySearch(Request $request)
    {
        $limit = 30;
        $offset = 0;
        if ($request->get('page') > 1) {
            $offset = (($request->get('page')-1)*$limit);
        }
        $searchQuery = '%'.$request->q.'%';
        $results = Category::select('slug', 'name', 'icon')
            ->where(function ($query) use ($searchQuery){
                $query->where('name', 'LIKE', $searchQuery);
            })->offset($offset)->limit($limit)->get();
        $result = [];
        foreach ($results as $k=>$r) {
            $result[] = [
                'id' => $r->slug,
                'slug' => $r->slug,
                'icon' => $r->icon,
                'name' => $r->name,
            ];
        }
        $count = Category::select('slug', 'name', 'icon')
            ->where(function ($query) use ($searchQuery){
                $query->where('name', 'LIKE', $searchQuery);
            })->count();
        return response()->json(["incomplete_results" => false, "items" => $result, "total_count" => $count]);
    }

    public function userSearch(Request $request)
    {
        $limit = 30;
        $offset = 0;
        if ($request->get('page') > 1) {
            $offset = (($request->get('page')-1)*$limit);
        }
        $searchQuery = '%'.$request->q.'%';
        $result = User::where('status', '1')->where('screen', '>=', '6')
            ->where(function ($query) use ($searchQuery){
                $query->where(function ($q) use($searchQuery){
                    $q->where('name', 'LIKE', $searchQuery)
                    ->orWhere('address', 'LIKE', $searchQuery)
                    ->orWhere('about', 'LIKE', $searchQuery);
                });
            })->offset($offset)->limit($limit)->get();
        $count = User::where('status', '1')->where('screen', '>=', '6')
            ->where(function ($query) use ($searchQuery){
                $query->where(function ($q) use($searchQuery){
                    $q->where('name', 'LIKE', $searchQuery)
                    ->orWhere('address', 'LIKE', $searchQuery)
                    ->orWhere('about', 'LIKE', $searchQuery);
                });
            })->count();
        return response()->json(["incomplete_results" => false, "items" => $result, "total_count" => $count]);
    }

    public function cashoutsList($id) {
        $cashouts = Cashout::where('donation_request_id', $id)->get();
        return view('admin.cashouts-list', compact('cashouts'));
    }

    public function donationDelete($id) {
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $donations = DonationRequest::find($id);
        if($donations->delete()) {
            return redirect()->route('donations-list')->with('info', "DoleUpp Request Deleted Successfully.");
        }else{
            return redirect()->route('donations-list')->with('error', "Error! Please Try Again.");
        }
    }

    public function donateNow(){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $users=User::all();
        return view('admin.donate-now', compact('users'));
    }

    public function faqList(){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $faq = Faq::all();
        return view('admin.faqs-list', compact('faq'));
    }

    public function faqStore(Request $request){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);
        $input = $request->only('question', 'answer');
        $insert = Faq::create($input);
        if($insert) {
            return redirect()->route('faqs-list')->with('info', "FAQ Added Successfully.");
        }else{
            return redirect()->route('faqs-list')->with('error', "Not Added Successfully.");
        }
    }

    public function faqEdit(Request $request){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);
        $faq = Faq::where('id', request('id'))->first();
        $faq->question = request('question');
        $faq->answer = request('answer');
        if($faq->save()) {
            return redirect()->route('faqs-list')->with('info', "FAQ Updated Successfully.");
        }else{
            return redirect()->route('faqs-list')->with('error', "Error! Please Try Again.");
        }
    }

    public function faqDelete($id){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $faq = Faq::find($id);
        if($faq->delete()) {
            return redirect()->route('faqs-list')->with('info', "FAQ Deleted Successfully.");
        }else{
            return redirect()->route('faqs-list')->with('error', "Error! Please Try Again.");
        }
    }

    public function newsCategoryList(){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $newscategory = NewsCategory::all();
        return view('admin.news-category-list', compact('newscategory'));
    }

    public function newsCategoryStore(Request $request){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $request->validate([
            'name' => 'required',
        ]);
        $input = $request->only('name');
        $insert = NewsCategory::create($input);
        if($insert) {
            return redirect()->route('news-category-list')->with('info', "News Category Added Successfully.");
        }else{
            return redirect()->route('news-category-list')->with('error', "Not Added Successfully.");
        }
    }

    public function newsCategoryEdit(Request $request){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $request->validate([
            'name' => 'required',
        ]);
        $newscategory = NewsCategory::where('id', request('id'))->first();
        $newscategory->name = request('name');
        $newscategory->status = request('status');
        if($newscategory->save()) {
            return redirect()->route('news-category-list')->with('info', "News Category Updated Successfully.");
        }else{
            return redirect()->route('news-category-list')->with('error', "Error! Please Try Again.");
        }
    }

    public function newsCategoryDelete($id){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $newscategory = NewsCategory::find($id);
        if($newscategory->delete()) {
            return redirect()->route('news-category-list')->with('info', "News Category Deleted Successfully.");
        }else{
            return redirect()->route('news-category-list')->with('error', "Error! Please Try Again.");
        }
    }

    public function newsList(){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $news = News::all();
        $newscategory = NewsCategory::where('status',"Active")->get();
        return view('admin.news-list', compact('news','newscategory'));
    }

    public function newsStore(Request $request){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $request->validate([
            'news_category_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'type' => 'required',
            'imgae' => 'required_if:type,image|mimes:jpg,png,jpeg',
            'video' => 'required_if:type,image|mimes:mp4',
        ]);
        $input = $request->only('news_category_id', 'title', 'description', 'type');
        if($request->type == "image") {
            if($request->imgae) {
                $imageName = $request->imgae->store('images/news');
                $input['imgae'] = asset('storage/' . $imageName);
            }
        }elseif ($request->type == "video") {
            $videoName = $request->video->store('/videos');
            $input['video'] = url('storage').'/'.$videoName;
            $videoFrom = explode('/', $videoName);
            $imageTo = uniqid().time().'.png';
            $thumbnail = url('storage/videos/thumbnail').'/'.$imageTo;
            FFMpeg::fromDisk('videos')
            ->open($videoFrom[1])
            ->getFrameFromSeconds(1)
            ->export()
            ->toDisk('thumbnail')
            ->save($imageTo);
            $input['thumbnail'] = $thumbnail;
        }
        $input['slug'] = str_replace(' ', '-',strtolower($request->title));
        $insert = News::create($input);
        if($insert) {
            return redirect()->route('news-list')->with('info', "News Added Successfully.");
        }else{
            return redirect()->route('news-list')->with('error', "Not Added Successfully.");
        }
    }

    public function newsEdit($id){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $news = News::where('id', $id)->first();
        $newscategory = NewsCategory::where('status',"Active")->get();
        return view('admin.news-edit', compact('news', 'newscategory'));
    }

    public function newsUpdate(Request $request,$id){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $request->validate([
            'news_category_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'imgae' => 'mimes:jpg,png,jpeg',
            'video' => 'mimes:mp4'
        ]);
        $news = News::where('id', $id)->first();
        $news->news_category_id = request('news_category_id');
        $news->title = request('title');
        $news->slug = str_replace(' ', '-',strtolower($news->title));
        $news->description = request('description');
        $news->type = request('type');

        if(request('type') == "image") {
            if($request->imgae) {
                $imageName = $request->imgae->store('images/news');
                $news->imgae = asset('storage/' . $imageName);
            }
        }elseif (request('type') == "video") {
            $videoName = $request->video->store('/videos');
            $news->video = url('storage').'/'.$videoName;
            $videoFrom = explode('/', $videoName);
            $imageTo = uniqid().time().'.png';
            $thumbnail = url('storage/videos/thumbnail').'/'.$imageTo;
            FFMpeg::fromDisk('videos')
            ->open($videoFrom[1])
            ->getFrameFromSeconds(1)
            ->export()
            ->toDisk('thumbnail')
            ->save($imageTo);
            $news->thumbnail = $thumbnail;
        }
        if($news->save()) {
            return redirect()->route('news-list')->with('info', "News Updated Successfully.");
        }else{
            return redirect()->route('news-list')->with('error', "Error! Please Try Again.");
        }
    }

    public function newsDelete($id){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $news = News::find($id);
        if($news->delete()) {
            return redirect()->route('news-list')->with('info', "News Deleted Successfully.");
        }else{
            return redirect()->route('news-list')->with('error', "Error! Please Try Again.");
        }
    }

    public function contactList(){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $contact = Contact::all();
        return view('admin.contact-list', compact('contact'));
    }

    public function contactReply($id){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $contact_reply = Contact::where('id',$id)->first();
        return view('admin.contact-reply', compact('contact_reply'));
    }

    public function contactReplyView($id){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $contact_reply = Contact::where('id',$id)->first();
        return view('admin.contact-reply-view', compact('contact_reply'));
    }

    public function contactReplyUpdate(Request $request, $id){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $input = $request->only('reply');
        $input['reply_status'] = "replied";
        $update = Contact::where('id', $id)->update($input);

        $data = Contact::where('id', $id)->first();
        $details = [
                    'title' => 'Dear '.$data->name,
                    'body' => 'Message: '.$data->message.' <br><br>
                    Reply: '.$data->reply.' <br><br>
                    Thankyou for Contact us.'
                ];
        \Mail::to($data->email)->send(new AuthMail($details));
        // \Mail::to("realhimanshubansal@gmail.com")->send(new AuthMail($details));
        if($update) {
            return redirect()->route('contact-list')->with('info', "Replied Successfully.");
        }else{
            return redirect()->route('contact-list')->with('error', "Error! Please Try Again.");
        }
    }

    public function settingsList(){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $setting = Setting::where('id',1)->first();
        return view('admin.settings-list', compact('setting'));
    }

    public function settingsHowItWorks(){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $setting = Setting::where('id',1)->first();
        return view('admin.settings-how-it-works', compact('setting'));
    }

    public function settingsVideoEdit(Request $request){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        try {
            $request->validate([
                'onboarding_video' => 'mimes:mp4',
                'onboarding_text' => 'string',
                'onboarding_text_2' => 'string',
                'onboarding_text_3' => 'string'
            ]);

            $input = $request->only('onboarding_text', 'onboarding_text_2', 'onboarding_text_3');
            if ($request->onboarding_video) {
                $videoName = $request->onboarding_video->store('/videos');
                $input['onboarding_video'] = url('storage').'/'.$videoName;
                $videoFrom = explode('/', $videoName);
                $imageTo = uniqid().time().'.png';
                $thumbnail = url('storage/videos/thumbnail').'/'.$imageTo;
                FFMpeg::fromDisk('videos')
                ->open($videoFrom[1])
                ->getFrameFromSeconds(1)
                ->export()
                ->toDisk('thumbnail')
                ->save($imageTo);
                $input['thumbnail'] = $thumbnail;
            }
            $update = Setting::where('id', request('setting_id'))->update($input);

            return redirect()->route('settings-how-it-works')->with('info', "How it Works Updated Successfully.");
        } catch (Exception $e) {
            return redirect()->route('settings-how-it-works')->with('error', "Error! Please Try Again.");
        }
    }

    public function settingsAmountEdit(Request $request){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        try {
            $request->validate([
                'admin_commission' => 'required',
                'cash_out_fee' => 'required',
                'cash_out_day' => 'required',
            ]);

            $input = $request->only('admin_commission', 'cash_out_fee', 'cash_out_day', 'cash_out_note',);
            $update = Setting::where('id', request('id'))->update($input);
            return redirect()->route('settings-list')->with('info', "Amount Details Updated Successfully.");
        } catch (Exception $e) {
            return redirect()->route('settings-list')->with('error', "Error! Please Try Again.");
        }
    }

    public function commentsList(){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $comments = Comment::latest()->get();
        return view('admin.comments-list', compact('comments'));
    }

    public function pageList(){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $pages = Pages::all();
        return view('admin.pages-list', compact('pages'));
    }

    public function pageEdit($id){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $pages = Pages::where('id', $id)->first();
        return view('admin.pages-edit', compact('pages'));
    }

    public function pageView($id){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $pages = Pages::where('id', $id)->first();
        return view('admin.pages-view', compact('pages'));
    }

    public function pageUpdate(Request $request, $id){
        if (!$this->is_admin()) {
            return redirect('admin/dashboard')->with('error',"You are not allowed!");
        }
        $request->validate([
            'content' => 'required',
        ]);
        $pages = Pages::where('id', $id)->first();
        $pages->content = request('content');
        if($pages->save()) {
            return redirect()->route('pages-list')->with('info', $pages->title." Page Updated Successfully.");
        }else{
            return redirect()->route('pages-list')->with('error', "Error! Please Try Again.");
        }
    }

    public function subadminActivities(Request $request)
    {
        $data = Log::with('user')->orderBy('id', 'desc')->where('user_id', 2);

//        if ($request->has('log_type')) {
//            $data = $data->where('log_type', request('log_type'));
//        }
//        if ($request->has('table')) {
//            $data = $data->where('table_name', request('table'));
//        }
//        if ($request->has('from_date') && $request->has('to_date')) {
//            $from = request('from_date') . " 00:00:00";
//            $to = request('to_date') . " 23:59:59";
//            $data = $data->whereBetween('log_date', [$from, $to]);
//        }

        $activities = $data->paginate(10);

        return view('admin.subadmin-activities', compact('activities'));
    }

    public function feedBacks(Request $request)
    {
        $feedbacks = Feedback::with('user', 'donation')->orderByDesc('id')->paginate(10);
        return view('admin.feedbacks', compact('feedbacks'));
    }

    public function subscriptions(Request $request)
    {
        $subscriptions = Subscription::with('user')->whereHas('user')->orderByDesc('id')->paginate(10);
        return view('admin.subscriptions', compact('subscriptions'));
    }

    public function notifications(Request $request)
    {
        $notifications = Notification::with('user')->whereHas('user', function ($q){
            $q->whereNotNull('email');
        })->orderByDesc('id')->paginate(10);
        return view('admin.notifications', compact('notifications'));
    }

    public function notificationToAll(Request $request)
    {
        try {
            $rules = [
                'title' => 'required',
                'notification' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->getMessageBag(), 'message' => ''], 422);
            }

            $notification = [
                'title' => $request->title,
                'body' => $request->notification
            ];
            if($request->hasFile('image')) {
                $image = $request->image->store(public_path('images/profile'));
                $image = asset($image);
            } else {
                $image = asset('assets/img/footer-logo.svg');
            }
            $users = User::where('status', '1')->where('notification', 'Yes')->get();
            foreach ($users as $user){
                $extraNotificationData = $notification;
                $extraNotificationData['type'] = 'admin';
                $extraNotificationData['image'] = $image;
                sendNotification($user->id, $notification, $extraNotificationData);
            }
            return redirect()->back()->withSuccess('Notification send successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function ratings(Request $request)
    {
        $ratings = Rating::with('user', 'donation')->whereHas('user', function ($q){
            $q->whereNotNull('email');
        })->orderByDesc('id')->paginate(10);
        return view('admin.ratings', compact('ratings'));
    }

    public function donationRequestReports(Request $request){
        if($request->ajax()) {
            $data = DonationRequestReport::with(['user', 'reasons', 'donation_request'])->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function ($query) {
                    return '<a href="'.url('/admin/user-details', $query->user['id']).'"><font color="blue">'.$query->user['name'].'</font></a>';
                })->addColumn('category', function ($query) {
                    return @$query->donation_request->category->name;
                })->addColumn('video', function ($query) {
                    return '<a href="'.$query->donation_request->video.'" class="ply-btn"><img height="80" src="'.$query->donation_request->thumbnail.'"></a>';
                })->editColumn('created_at', function ($query) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $query->created_at)->format('m-d-Y');
                })->rawColumns(['user', 'category', 'video', 'created_at'])
                ->make(true);
        }
        return view('admin.donation-request-reports.index');
    }
}
