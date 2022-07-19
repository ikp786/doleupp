<?php

use App\Models\FcmToken;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

function setting($value)
{
    $find = Setting::query()->first();
    return $find->$value;
}

function setJsonData($value)
{
    array_walk_recursive($value, function (&$item, $key) {
        $item = null === $item ? '' : strval($item);
    });
    return $value;
}

function sendNotification($user_id, $notification, $extraNotificationData)
{
    try {
        $accessToken = env('FCM_API_KEY');

        $input = [
            'user_id' => $user_id,
            'notification_type' => $extraNotificationData['type'],
            'notification' => $extraNotificationData
        ];
        Notification::create($input);

        $find = User::where('notification', 'Yes')->find($user_id);
        $fcm_tokens = FcmToken::select('token')->where('user_id', $user_id)->get();
        if($find && $fcm_tokens->count() > 0) {
            $tokenList = [];
            foreach ($fcm_tokens as $token) {
                $tokenList[] = $token->token;
            }

            $fcmNotification = [
                'registration_ids' => $tokenList, //multiple token array
                //'to' => $fcm_token, //single token
                'notification' => $notification,
                'data' => $extraNotificationData
            ];

            $headers = [
                'Authorization: key=' . $accessToken,
                'Content-Type: application/json'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
            $result = curl_exec($ch);
            curl_close($ch);
            Log::info($result);
            return response()->json(['status' => true, 'msg' => 'Notification sent successfully.', 'data' => $result]);
        }
        return response()->json(['status' => true, 'msg' => 'Notification sent successfully.']);
    } catch (\Exception $e) {
        Log::error($e);
        return response()->json(['status' => false, 'error' => $e->getMessage()]);
    }
}

function sendSMS($messageid, $variables_values, $numbers) {
    try {
        $data = [
            'authorization' => env('SMS_API_KEY'),
            'sender_id' => env('SMS_SENDER_ID'),
            'message' => $messageid,
            'variables_values' => $variables_values,
            'route' => 'dlt',
            'numbers' => $numbers
        ];
        $response = Http::get('https://www.fast2sms.com/dev/bulkV2', $data);
        return response()->json(['status' => true, 'msg' => 'SMS sent successfully.', 'data' => $response]);
    } catch (\Exception $e) {
        Log::error($e);
        return response()->json(['status' => false, 'error' => $e->getMessage()]);
    }
}

function quickSendSMS($message, $numbers) {
    try {
        $data = [
            'authorization' => env('SMS_API_KEY'),
            'sender_id' => env('SMS_SENDER_ID'),
            'message' => $message,
            'language' => 'english',
            'route' => 'q',
            'numbers' => $numbers
        ];
        $response = Http::get('https://www.fast2sms.com/dev/bulkV2', $data);
        return response()->json(['status' => true, 'msg' => 'SMS sent successfully.', 'data' => $response]);
    } catch (\Exception $e) {
        Log::error($e);
        return response()->json(['status' => false, 'error' => $e->getMessage()]);
    }
}
