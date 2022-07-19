<?php

namespace App\Providers;

use App\Models\BannedWord;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('banned_words', function($attribute, $value, $parameters) {
            // Banned words
            $words = BannedWord::all();
            foreach ($words as $word)
            {
                if (stripos($value, $word->word) !== false) return false;
            }
            return true;
        });

        Validator::extend('without_spaces', function($attribute, $value){
            return preg_match('/^\S*$/u', $value);
        });

        Validator::extend('email_not_allowed', function($attribute, $value){
            if(preg_match('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/si', $value)) {
                return false;
            }
            return true;
        });

        Validator::extend('phone_not_allowed', function ($attribute, $value) {
            $new_value = preg_replace('/[^a-zA-Z0-9]+/', '', $value);
            if(preg_match('/[1-9][0-9]{6,9999}$/', $new_value)) {
                return false;
            }
            return true;
        });

        Validator::extend('website_not_allowed', function ($attribute, $value) {
            $words = array('https', 'http', 'www', 'dotcom', '.com');
            foreach ($words as $word)
            {
                if (stripos($value, $word) !== false) return false;
            }
            return true;
        });
    }
}
