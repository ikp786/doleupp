<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\StripeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('unauthorized', function () {
    return response()->json(['statusCode' => 401, 'message' => 'Unauthorized user.']);
})->name('api.unauthorized');

Route::post('signup', [AuthController::class, 'signup']);
Route::post('signup-otp-verification', [AuthController::class, 'signup_otp_verification']);
Route::post('signin', [AuthController::class, 'signin']);

Route::post('password/reset', [AuthController::class, 'otpResend']);
Route::post('password/otp-verification', [AuthController::class, 'otpVerify']);
Route::post('password/update', [AuthController::class, 'passwordReset']);

Route::get('make-offline', [UserController::class, 'makeOffline']);

Route::get('contact/reasons', [UserController::class, 'reasonList']);
Route::post('contact/create', [UserController::class, 'contactCreate']);
Route::get('faqs', [UserController::class, 'faqs']);
Route::get('settings', [UserController::class, 'settings']);
Route::get('banks', [UserController::class, 'banks']);
Route::get('cities', [UserController::class, 'cities']);

Route::get('subscription/payment/return', [UserController::class, 'subscriptionPaymentStatus'])->name('subscription.apistatus');
Route::get('subscription/payment/status', [UserController::class, 'showSubscriptionPaymentStatus'])->name('subscription.payment-status');

Route::get('donate/payment/return', [UserController::class, 'donatePaymentStatus'])->name('donate.apistatus');
Route::get('donate/payment/status', [UserController::class, 'donatePaymentStatusApi'])->name('donate.payment-status');

Route::get('guest-donations', [UserController::class, 'donationList']);
Route::get('donation/categories', [UserController::class, 'categories']);

Route::post('reasons', [UserController::class, 'reasons']);
Route::get('roles', [UserController::class, 'rolesList']);
Route::middleware('auth:api')->group( function () {
    Route::get('donations', [UserController::class, 'donationList']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('password-change', [AuthController::class, 'passwordChange']);

    Route::post('profile/create', [UserController::class, 'profileCreate']);
    Route::post('profile/edit', [UserController::class, 'profileEdit']);
    Route::post('profile/iam', [UserController::class, 'iam']);
    Route::post('profile/about', [UserController::class, 'profileAbout']);
    Route::get('profile/detail', [UserController::class, 'profileDetail']);
    Route::get('profile/{id}', [UserController::class, 'profileDetails']);
    Route::match(['get', 'post'], 'make-online', [UserController::class, 'makeOnline']);
    Route::post('notification/update', [UserController::class, 'notificationUpdate']);

    Route::get('security-questions', [UserController::class, 'securityQuestionList']);
    Route::post('user-security-question/create', [UserController::class, 'userSecurityQuestionCreate']);
    Route::get('user-security-question/list', [UserController::class, 'userSecurityQuestionList']);

    Route::post('bank/create', [UserController::class, 'bankCreate']);
    Route::get('bank/detail', [UserController::class, 'bankDetail']);

    Route::post('card/create', [UserController::class, 'cardCreate']);
    Route::get('card/detail', [UserController::class, 'cardDetail']);

    Route::post('donation-request/create', [UserController::class, 'donationRequestCreate']);
    Route::post('donation-request/delete', [UserController::class, 'donationRequestDelete']);
    Route::post('donation-request/report', [UserController::class, 'donationRequestReport']);
    Route::get('donation-requests', [UserController::class, 'donationRequestList']);
    Route::get('donation-request/{id}', [UserController::class, 'donationRequestDetail']);
    Route::post('donation/comment', [UserController::class, 'donationComment']);
    Route::post('donation/view', [UserController::class, 'donationView']);
    Route::post('donation/share', [UserController::class, 'donationShare']);
    Route::post('donation-wishlist/create', [UserController::class, 'donationWishlistCreate']);
    Route::post('donation-wishlist/remove', [UserController::class, 'donationWishlistRemove']);
    Route::get('donation-wishlists', [UserController::class, 'donationWishlistList']);
    Route::get('donation/{id}/donors', [UserController::class, 'donationDonor']);
    Route::get('my-donor', [UserController::class, 'myDonor']);
    Route::get('my-donation', [UserController::class, 'myDonation']);
    Route::post('donation/ready-to-pay', [UserController::class, 'donationReadyToPay']);
    Route::post('donation/make-payment', [UserController::class, 'donationMakePayment']);
    Route::post('donation/make-payment/video', [UserController::class, 'donationMakePaymentVideo']);

    Route::get('my-wallet', [UserController::class, 'myWallet']);
    Route::get('fee-amount-for-donate', [UserController::class, 'amountForDonate']);
    Route::get('donation/ready-to-cashout', [UserController::class, 'cashoutList']);
    Route::post('donation/make-cashout', [UserController::class, 'donationCashOut']);

    Route::get('news', [UserController::class, 'newsList']);
    Route::get('news/{id}', [UserController::class, 'newsDetail']);

    Route::get('notifications', [UserController::class, 'notificationList']);

    Route::post('rate-to-reel', [UserController::class, 'rateToReel']);
    Route::post('get-my-rating', [UserController::class, 'getMyRating']);

    Route::get('subscription', [UserController::class, 'subscription_pay']);
    Route::post('ios/subscription', [UserController::class, 'makeIosSubscription']);

    Route::get('users', [UserController::class, 'usersList']);

    Route::post('corporate-donation', [UserController::class, 'corporateDonationPost']);
    Route::get('corporate-donation/list', [UserController::class, 'corporateDonationList']);
    Route::get('corporate-donation/detail', [UserController::class, 'corporateDonationDetail']);

    Route::post('update/setting', [UserController::class, 'updateSetting']);
    Route::post('update/setting/verify', [UserController::class, 'updateSettingVerify']);
});
Route::get('stripe', [StripeController::class, 'index']);
Route::get('stripe/{paymentby}', [StripeController::class, 'index']);
Route::get('stripedonation', [StripeController::class, 'donation']);
Route::post('striperesponse', [StripeController::class, 'stripePost'])->name('stripe.post');
Route::post('striperesponsedonation', [StripeController::class, 'striperesponsedonation']);

Route::get('googlepay', [StripeController::class, 'googlepay']);
Route::get('googlepaydonation', [StripeController::class, 'googlepaydonation']);
Route::post('gpayresponse', [StripeController::class, 'gpayresponse']);
Route::post('gpayresponsedonation', [StripeController::class, 'gpayresponsedonation']);

