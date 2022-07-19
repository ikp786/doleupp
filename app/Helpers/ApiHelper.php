<?php


namespace App\Helpers;

use App\Models\Donation;
use App\Models\DonationRequest;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ApiHelper
{
    public static function amount_for_redeem($user_id)
    {
        return Donation::whereIn('status', ['earned'])->where('donation_to', $user_id)->sum('amount');
    }

    public static function toRef($data){
        $alphabet =   array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
        $alpha_flip = array_flip($alphabet);
        //$data = $data-1;
        $ceil = ceil(($data+1)/1000)-1;
        if($ceil <= 25){
            $newAlpha = $alphabet[$ceil];
        }elseif($ceil > 25){
            $dividend = $ceil; //($data + 1);
            $alpha = '';
            $modulo = 1;
            while ($dividend > 0){
                $modulo = ($dividend - 1) % 26;
                $alpha = $alphabet[$modulo] . $alpha;
                $dividend = floor((($dividend - $modulo) / 26));
            }
            $newAlpha = $alpha;
        }
        //return $newAlpha;
        if($ceil >0 ){
            $ceil -= 1;
        }
        $ceil2 = ceil($ceil/26);

        if($ceil2 <= 25){
            $newAlpha2 = $alphabet[$ceil2];
        }elseif($ceil2 > 25){
            $dividend = $ceil2; //($data + 1);
            $alpha = '';
            $modulo = 1;
            while ($dividend > 0){
                $modulo = ($dividend - 1) % 26;
                $alpha = $alphabet[$modulo] . $alpha;
                $dividend = floor((($dividend - $modulo) / 26));
            }
            $newAlpha2 = $alpha;
        }

        $num = intval(substr($data, -3));
        if($num < 10) {
            $newNum = '000'.$num;
        } elseif($num >=10 && $num < 100) {
            $newNum = '00'.$num;
        } elseif($num >=100 && $num < 1000) {
            $newNum = '0'.$num;
        } else {
            $newNum = $num;
        }
        return 'DoleUpp'.strtoupper($newAlpha2).strtoupper($newAlpha).$newNum;
    }

    public static function referral_users()
    {
        $user=User::find(auth()->user()->id);
        if($user) {
            $settings = Setting::find(1);
            $donation_amount = $settings->donation_price;

            $now = Carbon::now();
            $date = Carbon::parse($user->created_at);
            $diff = $date->diffInYears($now);
            $starts_from = $date->addYears($diff)->startOfDay();
            $ends_at = Carbon::parse($starts_from)->addYear()->subSecond();
            $donation = DonationRequest::where('user_id', auth()->user()->id)->whereIn('status', ['Approved', 'Pending'])
                ->where('created_at', '>=', $starts_from)->where('created_at', '<=', $ends_at)->sum('donation_amount');
            return $donation_amount-$donation;
        } else {
            return 0;
        }
        /*$now=Carbon::now()->format('Y-m-d H:i:s');
        $sub=Subscription::where("user_id",auth()->user()->id)->where("status","Success")
            ->where('starts_from', '<=', $now)->where('ends_at', '>=', $now)->first();
        if($sub) {
            $settings = Setting::find(1);
            $donation_amount = $settings->donation_price;
            $donation = DonationRequest::where('user_id', auth()->user()->id)->whereIn('status', ['Approved', 'Pending'])
                ->where('created_at', '>=', $sub->starts_from)->where('created_at', '<=', $sub->ends_at)->sum('donation_amount');
            return $donation_amount-$donation;
        } else {
            return 0;
        }*/
    }

    public static function referral_users_old()
    {
        $settings = Setting::find(1);
        $subscriptionDate=Subscription::where("user_id",auth()->user()->id)->where("status","Success")->orderBy("id","DESC")->first();

        $date = Carbon::parse(@$subscriptionDate->created_at);
        $now = Carbon::now();

        // $diff = $date->floatDiffInMonths($now);
        $diff = ceil($date->floatDiffInMonths($now)) / 6;
        $diff = floor($diff) * 6;
        $newDate = Carbon::parse(@$subscriptionDate->created_at)->addMonths($diff);
        $users = Referral::where('referral_by', auth()->user()->id)->where('created_at', '>=', $newDate)->count();
        $users_10 = floor(($users % 50) / 10) * 100;
        $users_50 = floor($users / 50) * 250;
        $donation_amount = $settings->donation_price +$users_10+$users_50;
        $donation = DonationRequest::where('user_id', auth()->user()->id)->where('created_at', '>=', $newDate)->whereIn('status', ['Approved', 'Pending'])->sum('donation_amount');
        return $donation_amount-$donation;
    }

    public static function videoVerification($video_url)
    {
        $params = array(
            'stream_url' =>  $video_url,
            'workflow' => env('SIGHTENGINE_WORKFLOW_ID'),
            // specify where you want to receive callbacks
            'callback_url' => env('SIGHTENGINE_CALLBACK_URL'),
            'api_user' => env('SIGHTENGINE_API_KEY'),
            'api_secret' => env('SIGHTENGINE_API_SECRET'),
        );

        // this example uses cURL
        $ch = curl_init('https://api.sightengine.com/1.0/video/check-workflow.json?'.http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $output = json_decode($response, true);

        $media_id = '';
        if($output['status'] == 'success') {
            $media_id = $output['media']['id'];
        }
        return $media_id;
    }

    public static function videoVerify($media_id)
    {
        $params = array(
            'id' => $media_id,
            //'workflow' => env('SIGHTENGINE_WORKFLOW_ID'),
            'api_user' => env('SIGHTENGINE_API_KEY'),
            'api_secret' => env('SIGHTENGINE_API_SECRET'),
        );

        // this example uses cURL
        $ch = curl_init('https://api.sightengine.com/1.0/video/byid.json?'.http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $output = json_decode($response, true);
    }

    public static function imageVerification($image_url)
    {
        $params = array(
            'url' =>  $image_url,
            'workflow' => env('SIGHTENGINE_IMAGE_WORKFLOW_ID'),
            'api_user' => env('SIGHTENGINE_API_KEY'),
            'api_secret' => env('SIGHTENGINE_API_SECRET'),
        );

        $ch = curl_init('https://api.sightengine.com/1.0/check-workflow.json?'.http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $output = json_decode($response, true);

        // return json_encode($output);

        if($output['status'] == 'success') {
            if($output['summary']['action']=='reject') {
                return $output['summary']['reject_reason'][0]['text'];
                // handle image rejection
                // the rejection probability is provided in $output['summary']['reject_prob']
                // and user readable reasons for the rejection are in the array $output['summary']['reject_reason']
            }
        } else {
            return $output['error']['message'];
        }
        return 'success';
    }

    public static function dataByTableAndId($table, $id)
    {
        return DB::table($table)->find($id);
    }
}
