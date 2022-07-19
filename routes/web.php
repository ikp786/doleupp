<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('phpinfo', function () {
    return phpinfo();
});

Route::get('create-paypal-plan', 'App\Http\Controllers\PublicController@create_plan')->name('paypal.plan');
Route::get('subscribe/paypal', 'App\Http\Controllers\PublicController@paypalRedirect')->name('paypal.redirect');
Route::get('subscribe/paypal/return', 'App\Http\Controllers\PublicController@paypalReturn')->name('paypal.return');
Route::post('subscribe/paypal/cancel', 'App\Http\Controllers\PublicController@paypalCancel')->name('paypal.cancel');

Auth::routes();

Route::get('/', [PublicController::class, 'index'])->name('index');
Route::get('how-it-works', [PublicController::class, 'how_it_works'])->name('how-it-works');
Route::get('help-center', [PublicController::class, 'help_center'])->name('help-center');
Route::get('faq', [PublicController::class, 'faq'])->name('faq');
Route::get('doleupp-tips', [PublicController::class, 'doleupp_tips'])->name('doleupp-tips');
Route::get('about-us', [PublicController::class, 'how_it_works'])->name('about-us');
Route::get('contact-us', [PublicController::class, 'contact'])->name('contact-us');
Route::post('contact-us', [PublicController::class, 'contactCreate']);
Route::get('privacy-policy', [PublicController::class, 'privacy_policy'])->name('privacy-policy');
Route::get('terms-and-conditions', [PublicController::class, 'terms_and_conditions'])->name('terms-and-conditions');

Route::get('news', [PublicController::class, 'news'])->name('news');
Route::get('news/{slug}', [PublicController::class, 'news_detail'])->name('news.show');

Route::get('community', [PublicController::class, 'community'])->name('community');
Route::get('community/{slug}', [PublicController::class, 'community_detail'])->name('community.show');

Route::get('reel-share', [PublicController::class, 'reelShareModel'])->name('reel.share');

// Route::get('signin', [PublicController::class, 'signin'])->name('signin');
// Route::get('signup', [PublicController::class, 'signup'])->name('signup');
Route::post('login', [AuthController::class, 'login']);
Route::get('login-by-email', [AuthController::class, 'login_by_email'])->name('login.byemail')->middleware('guest');
Route::post('login-by-email', [AuthController::class, 'login_email'])->name('login.email');
Route::post('register', [AuthController::class, 'register']);
Route::get('otp-verification/{token}', [AuthController::class, 'otp_verification'])->name('otp-verification');
Route::post('otp-verification', [AuthController::class, 'verify']);

Route::get('auth/{provider}', [AuthController::class, 'redirectToProvider'])->name('auth.provider');
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth.callback');

Route::get('sightengine/video/callback', [PublicController::class, 'sightengineVideo'])->name('sightengine.video');

Route::get('gifs/{search}', [PublicController::class, 'gifs'])->name('gifs');
Route::get('reelSearch', [AdminController::class, 'reelSearch'])->name('reelSearch');
Route::get('categorySearch', [AdminController::class, 'categorySearch'])->name('categorySearch');
Route::get('userSearch', [AdminController::class, 'userSearch'])->name('userSearch');
Route::middleware('auth')->group( function () {
    Route::get('personal-information', [PublicController::class, 'personal_information'])->name('personal-information')->middleware('personalInformation');
    Route::post('personal-information', [PublicController::class, 'personalinformation']);
    Route::get('security-questions', [PublicController::class, 'security_questions'])->name('security-questions')->middleware('securityQuestions');
    Route::post('security-questions', [PublicController::class, 'securityquestions']);
    Route::get('banking-information', [PublicController::class, 'banking_information'])->name('banking-information')->middleware('bankingInformation');
    Route::post('banking-information', [PublicController::class, 'bankinginformation']);
    Route::get('add-card', [PublicController::class, 'add_card'])->name('add-card')->middleware('addCard');
    Route::post('add-card', [PublicController::class, 'addcard']);
    Route::get('i-am', [PublicController::class, 'i_am'])->name('i-am')->middleware('iam');
    Route::post('i-am', [PublicController::class, 'iam']);

    Route::middleware('checkStatus')->group( function () {
        Route::get('subscription', [PublicController::class, 'subscription'])->name('subscription');
        Route::post('subscription', [PublicController::class, 'subscription_pay'])->name('subscription.payment');
        Route::get('subscription/payment/status', [PublicController::class, 'subscriptionPaymentStatus'])->name('subscription.paymentstatus');
        Route::get('subscription-renew', [PublicController::class, 'subscription_renew'])->name('subscription-renew');

        Route::get('profile', [PublicController::class, 'profile'])->name('profile');
        Route::get('profile-edit', [PublicController::class, 'profile_edit'])->name('profile-edit');
        Route::post('profile-edit', [PublicController::class, 'profileedit']);

        Route::get('bank-detail-edit', [PublicController::class, 'bank_detail_edit'])->name('bank-detail-edit');
        Route::post('bank-detail-edit', [PublicController::class, 'bankdetailedit']);

        Route::get('donation-request', [PublicController::class, 'donation_request'])->name('donation-request');
        Route::get('donation-request/{id}/edit', [PublicController::class, 'donation_request_edit'])->name('donation-request-edit');
        Route::get('donation-request/{id}/delete', [PublicController::class, 'donation_request_delete'])->name('donation-request-delete');
        Route::post('donation-request', [PublicController::class, 'donationrequest']);
        Route::get('donation/payment/status', [PublicController::class, 'donationPaymentStatus'])->name('donationpaymentstatus');

        Route::post('donation/make-payment', [PublicController::class, 'donationMakePayment'])->name('donation.make-payment');
        Route::get('donate/payment/status', [PublicController::class, 'donatePaymentStatus'])->name('donate.paymentstatus');

        Route::get('fundraisers', [PublicController::class, 'fundraisers'])->name('fundraisers');
        Route::get('fundraisers/{slug}', [PublicController::class, 'fundraisers_details'])->name('fundraisers.show');
        Route::get('reels/{slug}', [PublicController::class, 'reel_details'])->name('reels.show');
        Route::get('reels/views/update', [PublicController::class, 'reel_views'])->name('reels.views');
        Route::get('reels/shares/update', [PublicController::class, 'reel_shares'])->name('reels.shares');

        Route::get('donation/wishlist/create', [PublicController::class, 'donationWishlistCreate'])->name('wishlist.create');
        Route::get('donation/wishlist/remove', [PublicController::class, 'donationWishlistRemove'])->name('wishlist.remove');

        Route::post('comments', [PublicController::class, 'donationComment'])->name('comments.store');
        Route::post('comments/gif', [PublicController::class, 'donationCommentGif'])->name('comments.gif');

        // Route::get('my-account', [HomeController::class, 'index'])->name('home');
        Route::get('my-account', [PublicController::class, 'my_account'])->name('home');
        Route::post('donation-to-lazor/make-payment', [PublicController::class, 'donationToLazor'])->name('donation.lazor');
        Route::get('donation-to-lazor/payment/status', [PublicController::class, 'donationToLazorStatus'])->name('donatation.lazorstatus');

        Route::get('lazor-reels', [PublicController::class, 'lazor_reels'])->name('lazor-reels');
        Route::get('my-donations', [PublicController::class, 'my_donations'])->name('my-donations');
        Route::get('my-holding-area', [PublicController::class, 'holding_area'])->name('holding-area');
        Route::get('my-wallet', [PublicController::class, 'my_wallet'])->name('my-wallet');
        Route::get('account-settings', [PublicController::class, 'account_settings'])->name('account-settings');
        Route::get('notification/status/update', [PublicController::class, 'notification'])->name('notification.status');
        Route::get('cashouts', [PublicController::class, 'donation_cashout'])->name('donation.cashout');
        Route::post('ready-to-cashout', [PublicController::class, 'readyToCashout'])->name('ready-to-cashout');
        Route::post('cashouts', [PublicController::class, 'donationCashOut']);

        Route::get('lazor-donations', [PublicController::class, 'lazorDontation'])->name('lazor-corporate');
        Route::get('lazor-donations/{id}', [PublicController::class, 'lazorDontationDetails'])->name('lazor-donations');
        Route::get('donors/{username}', [PublicController::class, 'donorProfile'])->name('donors');
        Route::get('amount-for-donate', [PublicController::class, 'amountForDonate'])->name('amountfor.donate');

        Route::get('reels-rating', [PublicController::class, 'reelsRating'])->name('reels.rating');
        Route::post('reels-rating', [PublicController::class, 'reelsRatingPost']);
        Route::get('success-rating', [PublicController::class, 'successRating'])->name('success.rating');
        Route::get('checkout', [PublicController::class, 'checkout']);

        Route::get('corporate-donation-categories', [PublicController::class, 'corporateCategories'])->name('corporate.categories');
        Route::get('corporate-donation', [PublicController::class, 'corporateDonation'])->name('corporate.donation');
        Route::post('corporate-donation', [PublicController::class, 'corporateDonationPost']);
        Route::get('corporate-donation-success', [PublicController::class, 'corporateSuccess'])->name('corporate.success');
        Route::get('corporate-donation-failed', [PublicController::class, 'corporateFailed'])->name('corporate.failed');
    });

    Route::match(['get', 'post'], 'make-online', [PublicController::class, 'makeOnline'])->name('make-online');
});

Route::middleware('admin')->prefix('/admin')->group( function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('users', [AdminController::class, 'usersList'])->name('users-list');
    Route::get('user-edit/{id}', [AdminController::class, 'usersEdit']);
    Route::post('user-update/{id}', [AdminController::class, 'usersDetailsUpdate'])->name('user-update');
    Route::get('user-status-update/{id}/{status}', [AdminController::class, 'usersStatusUpdate'])->name('user-status-update');

    Route::get('user-delete/{id}', [AdminController::class, 'usersDelete']);
    Route::get('user-details/{id}', [AdminController::class, 'usersDetailList']);

    Route::get('donations', [AdminController::class, 'donationList'])->name('donations-list');
    // Route::post('donation-status-update', [AdminController::class, 'donationStatusUpdate'])->name('donation-status-update');
    Route::get('donation-status-update/{id}/{status}', [AdminController::class, 'donationStatusUpdate'])->name('donation-status-update');
    Route::get('donation-delete/{id}', [AdminController::class, 'donationDelete']);
    Route::get('donors/{id}', [AdminController::class, 'donorsList']);
    Route::get('cashouts/{id}', [AdminController::class, 'cashoutsList']);

    Route::get('lazor-donations-list', [AdminController::class, 'lazorDonationsList'])->name('cd.list');
    Route::get('lazor-donations/{id}', [AdminController::class, 'lazorDonationsDetail'])->name('cd.detail');
    Route::get('donation-to-reels/{id}', [AdminController::class, 'donationToReels'])->name('cd.donation');
    Route::post('donation-to-reels/{id}', [AdminController::class, 'donationToReelsPost']);
    //Route::get('categorySearch', [AdminController::class, 'categorySearch'])->name('categorySearch');

    Route::get('faqs', [AdminController::class, 'faqList'])->name('faqs-list');
    Route::post('faqs-add', [AdminController::class, 'faqStore'])->name('faqs-add');
    Route::post('faqs-edit', [AdminController::class, 'faqEdit'])->name('faqs-edit');
    Route::get('faqs-delete/{id}', [AdminController::class, 'faqDelete']);


    Route::get('news-category', [AdminController::class, 'newsCategoryList'])->name('news-category-list');
    Route::post('news-category-add', [AdminController::class, 'newsCategoryStore'])->name('news-category-add');
    Route::post('news-category-edit', [AdminController::class, 'newsCategoryEdit'])->name('news-category-edit');
    Route::get('news-category-delete/{id}', [AdminController::class, 'newsCategoryDelete']);

    Route::get('news', [AdminController::class, 'newsList'])->name('news-list');
    Route::post('news-add', [AdminController::class, 'newsStore'])->name('news-add');
    Route::get('news-edit/{id}', [AdminController::class, 'newsEdit']);
    Route::post('news-update/{id}', [AdminController::class, 'newsUpdate'])->name('news-update');
    Route::get('news-delete/{id}', [AdminController::class, 'newsDelete']);

    Route::get('contacts', [AdminController::class, 'contactList'])->name('contact-list');
    Route::get('contacts-reply/{id}', [AdminController::class, 'contactReply']);
    Route::post('contacts-reply-update/{id}', [AdminController::class, 'contactReplyUpdate'])->name('contacts-reply-update');
    Route::get('contacts-reply-view/{id}', [AdminController::class, 'contactReplyView']);

    Route::get('settings', [AdminController::class, 'settingsList'])->name('settings-list');
    Route::get('settings-how-it-works', [AdminController::class, 'settingsHowItWorks'])->name('settings-how-it-works');
    Route::post('settings-video-edit', [AdminController::class, 'settingsVideoEdit'])->name('settings-video-edit');
    Route::post('settings-amount-edit', [AdminController::class, 'settingsAmountEdit'])->name('settings-amount-edit');
    Route::get('comments', [AdminController::class, 'commentsList'])->name('comments-list');

    Route::get('pages', [AdminController::class, 'pageList'])->name('pages-list');
    Route::post('pages-add', [AdminController::class, 'pageStore'])->name('pages-add');
    Route::get('pages-edit/{id}', [AdminController::class, 'pageEdit']);
    Route::get('pages-view/{id}', [AdminController::class, 'pageView']);
    Route::post('pages-update/{id}', [AdminController::class, 'pageUpdate'])->name('pages-update');
    Route::get('pages-delete/{id}', [AdminController::class, 'pageDelete']);

    Route::get('subadmin-activities', [AdminController::class, 'subadminActivities'])->name('subadmin-activities');
    Route::get('feedbacks', [AdminController::class, 'feedBacks'])->name('feedbacks');
    Route::get('subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
    Route::get('ratings', [AdminController::class, 'ratings'])->name('ratings');
    Route::get('notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::post('notifications', [AdminController::class, 'notificationToAll']);

    Route::get('donation-request-reports', [AdminController::class, 'donationRequestReports'])->name('admin.dr-reports');
});

Route::get('privacy-policy-app', [PublicController::class, 'privacy_policy_app']);
Route::get('terms-and-conditions-app', [PublicController::class, 'terms_and_conditions_app']);
Route::get('guarantee-policy-app', [PublicController::class, 'guarantee_policy_app']);
