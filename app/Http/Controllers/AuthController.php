<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\PhoneVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Str;

class AuthController extends Controller
{
    // public function page_redirect()
    // {
    //     if(auth()->user()->screen == 1) {
    //         return redirect(route('personal-information'));
    //     } elseif(auth()->user()->screen == 2) {
    //         return redirect(route('security-questions'));
    //     } elseif(auth()->user()->screen == 3) {
    //         return redirect(route('banking-information'));
    //     } elseif(auth()->user()->screen == 4) {
    //         return redirect(route('add-card'));
    //     } elseif(auth()->user()->screen == 5) {
    //         return redirect(route('i-am'));
    //     } else {
    //         return redirect(route('home'));
    //     }
    // }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if (Auth::user()->is_admin == 1) {
                return redirect('/admin/dashboard')->withSuccess('Welcome to DoleUpp.');
            }
            if(auth()->user()->screen == 1) {
                return redirect(route('personal-information'))->withSuccess('Welcome to DoleUpp.');
            } elseif(auth()->user()->screen == 2) {
                return redirect(route('security-questions'))->withSuccess('Welcome to DoleUpp.');
            } elseif(auth()->user()->screen == 3) {
                return redirect(route('banking-information'))->withSuccess('Welcome to DoleUpp.');
            } elseif(auth()->user()->screen == 4) {
                return redirect(route('add-card'))->withSuccess('Welcome to DoleUpp.');
            } elseif(auth()->user()->screen == 5) {
                return redirect(route('i-am'))->withSuccess('Welcome to DoleUpp.');
            } else {
                return redirect(route('index'))->withSuccess('Welcome to DoleUpp.');
            }
        }
        return redirect()->back()->withError('Invalid username or password.');
    }

    public function login_by_email()
    {
        return view('auth.login-email');
    }

    public function login_email(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if(auth()->user()->screen == 1) {
                return redirect(route('personal-information'))->withSuccess('Welcome to DoleUpp.');
            } elseif(auth()->user()->screen == 2) {
                return redirect(route('security-questions'))->withSuccess('Welcome to DoleUpp.');
            } elseif(auth()->user()->screen == 3) {
                return redirect(route('banking-information'))->withSuccess('Welcome to DoleUpp.');
            } elseif(auth()->user()->screen == 4) {
                return redirect(route('add-card'))->withSuccess('Welcome to DoleUpp.');
            } elseif(auth()->user()->screen == 5) {
                return redirect(route('i-am'))->withSuccess('Welcome to DoleUpp.');
            } else {
                return redirect(route('index'))->withSuccess('Welcome to DoleUpp.');
            }
        }
        return redirect()->back()->withError('Invalid username or password.');
    }

    public function register(Request $request)
    {
        try {
            $rules = [
                'country_code' => 'required',
                'phone' => 'required|numeric|regex:/^([0-9\s\-\+\(\)]*)$/|digits_between:6,15',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $error = '';
                if (!empty($validator->errors())) {
                    $error = $validator->errors()->first();
                }
                return response()->json(['success' => false, 'message' => $error], 200);
            }

            $find = User::where('phone', $request->phone)->whereNotNull('username')->exists();
            if ($find) {
                return response()->json(['success' => false, 'message' => 'This number is already registered, Please Login.'], 200);
                //return redirect()->back()->withError('This number is already registered, Please Login.');
            }
            $input = $request->only('country_code', 'phone');
            $input['otp'] = md5(1122);//md5(rand(1000, 9999));
            $input['token'] = Str::random(40) . '.' . time();
            $insert = PhoneVerification::create($input);
            if ($insert) {
                if($request->resend == 'Yes') {
                    return redirect(route('otp-verification', ['token' => $insert->token, 'country_code' => $insert->country_code, 'phone' => $insert->phone]))->withSuccess('OTP successfully send on your phone number.');
                }
                return response()->json(['success' => true, 'message' => 'OTP successfully send on your phone number.', 'data' => ['token' => $insert->token, 'country_code' => $insert->country_code, 'phone' => $insert->phone]], 200);
            }
            return response()->json(['success' => false, 'message' => 'Signup failed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 200);
        }
    }

    public function otp_verification($token,Request $request)
    {
        $phone = $request->get('phone');
        $country_code = $request->get('country_code');
        return view('auth.otp-verification', compact('token', 'phone', 'country_code'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|regex:/^([0-9\s\-\+\(\)]*)$/|digits_between:6,15',//unique:users,phone',
            'token' => 'required',
            'otp' => 'required|numeric|digits:4'
        ]);
        $find = PhoneVerification::where('phone', $request->phone)->where('token', $request->token)->where('otp', md5($request->otp))->first();
        if ($find) {
            $input = $request->only('phone');
            $input['country_code'] = $find->country_code;
            $input['phone_verified_at'] = Carbon::now();
            $id = [
                'phone' => $request->only('phone'),
            ];
            $user =  User::updateOrCreate($id, $input);
            Auth::login($user, true);
            return redirect(route('personal-information'))->withSuccess('You are registered successfully.');
        }
        return redirect()->back()->withError('Phone number registration failed or Invalid OTP.');
    }

    /**
     * Redirect the user to the Google|Facebook authentication page.
    *
    * @return \Illuminate\Http\Response
    */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Google|Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (InvalidStateException $e) {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect(route('login').'#');
        }
        // only allow people with @company.com to login
        // if(explode("@", $user->email)[1] !== 'company.com'){
        //     return redirect()->to('/');
        // }
        $find = User::where('email', $user->email)->first();
        if($find) {
            if($provider == 'google') {
                $update = ['google_id' => $user->id];
            } elseif($provider == 'facebook') {
                $update = ['facebook_id' => $user->id];
            }
            User::where('id', $find->id)->update($update);
            Auth::login($find);
        } else {
            $new = new User;
            $new->name = $user->name;
            $new->email = $user->email;
            if($provider == 'google') {
                $new->google_id = $user->id;
            } elseif($provider == 'facebook') {
                $new->facebook_id = $user->id;
            }
            //$new->avatar = $user->avatar;
            $new->image = $user->avatar_original;
            $new->save();
            Auth::login($new);
        }
        if(auth()->user()->screen == 1) {
            return redirect(route('personal-information'));
        } elseif(auth()->user()->screen == 2) {
            return redirect(route('security-questions'));
        } elseif(auth()->user()->screen == 3) {
            return redirect(route('banking-information'));
        } elseif(auth()->user()->screen == 4) {
            return redirect(route('add-card'));
        } elseif(auth()->user()->screen == 5) {
            return redirect(route('i-am'));
        } else {
            return redirect(route('index'));
        }
    }

    public function password_change()
    {
        return view('public.password-change');
    }

    public function passwordchange()
    {
        return view('public.password-change');
    }
}
