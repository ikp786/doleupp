<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use App\Models\UpdateSetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use App\Models\PhoneVerification;
use App\Models\User;
use Carbon\Carbon;
use Validator;
use Exception;
use Str;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        try {
            $rules = [
                'country_code' => 'required',
                'phone' => 'required',//,//|unique:users,phone',
            ];
            $messages = [
                'phone.required' => 'The phone no. field is required.',
                //'phone.numeric' => 'The phone no. must be a number',
                //'phone.digits' => 'The phone no. must be 10 digits.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error]);
            }

            $find = User::where('phone', $request->phone)->whereNotNull('username')->exists();
            if($find) {
                return response()->json(['statusCode' => 422, 'message' => 'This number is already registered, Please Login.']);
            }

            $input = $request->only('country_code', 'phone');
            $input['otp'] = md5(1122);//md5(rand(1000, 9999));
            $input['token'] = Str::random(40).'.'.time();
            $insert = PhoneVerification::create($input);
            if ($insert) {
                $data = PhoneVerification::find($insert->id);
                // $messageid='messsageid';
                // $variables_values = $request->prospect_name|$customer->name|$customer->name|$insert->otp|'url';
                // $numbers = $request->phone;
                // sendSMS($messageid, $variables_values, $numbers);
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'OTP successfully send on your phone number.', 'data' => $data]);
            }
            return response()->json(['statusCode' => 422, 'message' => 'OTP sending failed.']);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => $e->getMessage()]);
        }
    }

    public function signup_otp_verification(Request $request)
    {
        try {
            $rules = [
                'phone' => 'required',//unique:users,phone',
                'token' => 'required',
                'otp' => 'required|numeric|digits:4'
            ];
            $messages = [
                'phone.required' => 'The phone no. field is required.',
                //'phone.numeric' => 'The phone no. must be a number',
                //'phone.digits' => 'The phone no. must be 10 digits.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error]);
            }

            $find = User::where('phone', $request->phone)->whereNotNull('username')->exists();
            if($find) {
                return response()->json(['statusCode' => 422, 'message' => 'This number is already registered, Please Login.']);
            }
            $find = PhoneVerification::where('phone', $request->phone)->where('token', $request->token)->where('otp', md5($request->otp))->first();
            if ($find) {
                $input = $request->only('phone');
                $input['country_code'] = $find->country_code;
                $input['phone_verified_at'] = Carbon::now();
                // $data = User::create($input);
                $id = [
                    'phone' => $request->only('phone')
                ];
                $data = User::updateOrCreate($id, $input);
                //$data = PhoneVerification::find($insert->id);
                // $messageid='messsageid';
                // $variables_values = $request->prospect_name|$customer->name|$customer->name|$insert->otp|'url';
                // $numbers = $request->phone;
                // sendSMS($messageid, $variables_values, $numbers);
                $data['access_token'] =  $data->createToken('authToken')->accessToken;
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Your phone number register successfully.', 'data' => $data]);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Verification code id incorrect, Please try again.']);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => $e->getMessage()]);
        }
    }



    public function signin(Request $request)
    {
        try {
            $rules = [
                'email' => 'required',
                'password' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error]);
            }
            $input = $request->only('email', 'password');
            if (!auth()->attempt($input)) {
                return response()->json(['statusCode' => 422, 'message' => 'Invalid Credentials']);
            }
            if($request->device_token){
                $fcm = ['token' => $request->device_token];
                $fcm_token = ['token' => $request->device_token, 'user_id' => auth()->user()->id];
                FcmToken::updateOrCreate($fcm,$fcm_token);
            }
            $data = auth()->user();
            $data['access_token'] = auth()->user()->createToken('authToken')->accessToken;
            $data = setJsonData($data);
            return response()->json(['statusCode' => 200, 'message' => 'Login successfully.', 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => $e->getMessage()]);
        }
    }

    public function passwordChange(Request $request)
    {
        try {
            $rules = [
                'current_password' => 'required',
                'password' => 'required|confirmed|min:6',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }

            $data = User::where('id', auth()->user()->id)->first();
            $check = Hash::check($request->current_password, $data->password);
            if(!$check) {
                return response()->json(['statusCode' => 422, 'message' => 'Current password is not matched.']);
            }

            $input = $request->only('password');
            if ($data) {
                $input['password'] = Hash::make($input['password']);
                $data->update($input);
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Password changed successfully.', 'data' => $data]);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Password changing failed.']);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => $e->getMessage()]);
        }
    }

    public function logout(Request $request) {
        try {
            if (auth()->check()) {
                $request->user()->token()->revoke();
            }
            return response()->json(['statusCode' => 200, 'message' => 'You have been successfully logged out!']);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => $e->getMessage()]);
        }
    }

    public function otpResend(Request $request)
    {
        try {
            $rules = [
                'country_code' => 'required',
                'phone' => 'required'//|exists:users,phone',
            ];
            $messages = [
                'phone.required' => 'The phone no. field is required.',
                //'phone.numeric' => 'The phone no. must be a number',
                //'phone.digits' => 'The phone no. must be 10 digits.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error]);
            }

            $find = User::where('country_code', $request->country_code)->where('phone', $request->phone)->whereNotNull('username')->exists();
            if ($find) {
                $input = $request->only('country_code', 'phone');
                $input['otp'] = md5(1122);//md5(rand(1000, 9999));
                $input['token'] = Str::random(40) . '.' . time();
                $insert = PhoneVerification::create($input);
                if ($insert) {
                    $data = PhoneVerification::find($insert->id);
                    // $messageid='messsageid';
                    // $variables_values = $request->prospect_name|$customer->name|$customer->name|$insert->otp|'url';
                    // $numbers = $request->phone;
                    // sendSMS($messageid, $variables_values, $numbers);
                    $data = setJsonData($data->toArray());
                    return response()->json(['statusCode' => 200, 'message' => 'OTP successfully send on your phone number.', 'data' => $data]);
                }
                return response()->json(['statusCode' => 422, 'message' => 'OTP sending failed.']);
            }
            return response()->json(['statusCode' => 422, 'message' => 'This number is not registered, Please Register first.']);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => $e->getMessage()]);
        }
    }

    public function otpVerify(Request $request)
    {
        try {
            $rules = [
                'phone' => 'required',//unique:users,phone',
                'token' => 'required',
                'otp' => 'required|numeric|digits:4'
            ];
            $messages = [
                'phone.required' => 'The phone no. field is required.',
                //'phone.numeric' => 'The phone no. must be a number',
                //'phone.digits' => 'The phone no. must be 10 digits.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error]);
            }
            $find = PhoneVerification::where('phone', $request->phone)->where('token', $request->token)->where('otp', md5($request->otp))->first();
            if ($find) {
                $user = User::where('phone', $find->phone)->first();
                $data['token'] =  $user->password;
                return response()->json(['statusCode' => 200, 'message' => 'OTP verified successfully successfully.', 'data' => $data]);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Verification code id incorrect, Please try again.']);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => $e->getMessage()]);
        }
    }

    public function passwordReset(Request $request)
    {
        try {
            $rules = [
                'token' => 'required',
                'password' => 'required|min:6|confirmed',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['statusCode' => 422, 'message' => $error], 200);
            }
            $data = User::where('password', $request->token)->first();
            if(!$data) {
                return response()->json(['statusCode' => 422, 'message' => 'Invalid token.']);
            }
            $input = $request->only('password');
            if ($data) {
                $input['password'] = Hash::make($input['password']);
                $data->update($input);
                $data = setJsonData($data->toArray());
                return response()->json(['statusCode' => 200, 'message' => 'Password reset successfully.', 'data' => $data]);
            }
            return response()->json(['statusCode' => 422, 'message' => 'Password reset failed.']);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            if(auth()->check()) {
                Token::where('user_id', auth()->user()->id)->delete();

                User::where('id', auth()->user()->id)->delete();
                //$request->user()->token()->revoke();
            }
            return response()->json(['statusCode' => 200, 'message' => 'Your account have been deleted.']);
        } catch (Exception $e) {
            return response()->json(['statusCode' => 422, 'message' => 'Something went wrong.']);
        }
    }
}
