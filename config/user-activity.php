<?php

return [
    'activated'        => true, // active/inactive all logging
    'middleware'       => ['web', 'auth'],
    'route_path'       => 'admin/user-activity',
    'admin_panel_path' => 'admin/dashboard',
    'delete_limit'     => 7, // default 7 days

    'model' => [
        'user' => "App\Models\User",
        'bank' => "App\Models\Bank",
        'bank_detail' => "App\Models\BankDetail",
        'banned_word' => "App\Models\BannedWord",
        'card_detail' => "App\Models\CardDetail",
        'cashout' => "App\Models\Cashout",
        'category' => "App\Models\Category",
        'city' => "App\Models\City",
        'comment' => "App\Models\Comment",
        'contact' => "App\Models\Contact",
        'donation' => "App\Models\Donation",
        'donation_payment' => "App\Models\DonationPayment",
        'donation_request' => "App\Models\DonationRequest",
        'donation_user' => "App\Models\DonationUser",
        'faq' => "App\Models\Faq",
        'lazor_donation' => "App\Models\LazorDonation",
        'news' => "App\Models\News",
        'news_category' => "App\Models\NewsCategory",
        'notification' => "App\Models\Notification",
        'page' => "App\Models\Pages",
        'phone_verification' => "App\Models\PhoneVerification",
        'plan' => "App\Models\Plans",
        'reason' => "App\Models\Reason",
        'referral' => "App\Models\Referral",
        'security_question' => "App\Models\SecurityQuestion",
        'setting' => "App\Models\Setting",
        'share' => "App\Models\Share",
        'subscription' => "App\Models\Subscription",
        'user_security_question' => "App\Models\UserSecurityQuestion",
        'view' => "App\Models\View",
        'wishlist' => "App\Models\Wishlist",
    ],

    'log_events' => [
        'on_create'     => true, // false by default,
        'on_edit'       => true,
        'on_delete'     => true,
        'on_login'      => true,
        'on_lockout'    => true
    ]
];
