<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Exception;
use Twilio\Rest\Client;

class AuthController extends Controller
{
    /**
     * Summary of login
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth Management"},
     *     summary="User login",
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email or mobile number",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password (required if email is used)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="User logged in successfully"),
     *     @OA\Response(response="422", description="Validation errors or invalid credentials")
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            // 'password' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status' => false,
                'message' => $error,
                'data' => []
            ], 422);
        }

        if (str_contains($request->email, '@')) {
            if (!$request->password) {
                return response([
                    'status' => false,
                    'message' => 'The password field is required.',
                    'data' => []
                ], 422);
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // $user = Auth::user(); 
                $user = User::where('email', $request->email)->first();
                if ($user->email_verified_at) {
                    // $token = $user->createToken('usertoken')->plainTextToken;
                    $token = $user->createToken('usertoken')->plainTextToken;

                    return response([
                        'status' => true,
                        'message' => 'Signed in',
                        'data' => $user,
                        'token' => $token
                    ], 201);
                } else {
                    // $userId = Auth::id();
                    // $status = DB::table('personal_access_tokens')->where('user_id', $userId)->update([
                    //     'revoked' => 1,
                    //     'expires_at' => date('Y-m-d H:i:s'),
                    // ]);
                    return response([
                        'status' => false,
                        'message' => 'Please verify your email.',
                        'data' => []
                    ], 422);
                }
            }

            return response([
                'status' => false,
                'message' => 'Invalid credentials.',
                'data' => []
            ], 422);
        } else {
            $user = User::where('mobile_no', $request->email)->first();
            if (!$user) {
                return response([
                    'status' => false,
                    'message' => 'User not found.',
                    'data' => []
                ], 422);
            } else if ($user->mobile_verified_at) {
                // $token = $user->createToken('usertoken')->plainTextToken;

                // $otp = 1111;
                $otp = rand(1111, 9999);
                $message = "Login OTP is " . $otp;

                $account_sid = env("TWILIO_SID");
                $auth_token = env("TWILIO_TOKEN");
                $twilio_number = env("TWILIO_FROM");

                $client = new Client($account_sid, $auth_token);

                try {
                    $sms = $client->messages->create($user->country_code . '' . $request->email, [
                        'from' => $twilio_number,
                        'body' => $message
                    ]);

                    User::where('id', $user->id)->update(['otp' => $otp, 'otp_time' => date('Y-m-d H:i:s')]);

                    return response([
                        'status' => true,
                        'message' => 'OTP send',
                        'data' => $user,
                        'token' => null
                    ], 201);
                } catch (Exception $e) {
                    $otp = 1111;

                    User::where('id', $user->id)->update(['otp' => $otp, 'otp_time' => date('Y-m-d H:i:s')]);

                    return response([
                        'status' => true,
                        'message' => 'OTP send',
                        'data' => $user,
                        'token' => null
                    ], 201);
                }
            } else {
                return response([
                    'status' => false,
                    'message' => 'Please verify your mobile.',
                    'data' => []
                ], 422);
            }

            return response([
                'status' => false,
                'message' => 'Invalid credentials.',
                'data' => []
            ], 422);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/signup",
     *     tags={"Auth Management"},
     *     summary="Register a new user",
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *    @OA\Parameter(
     *        name="country_code",
     *       in="query",
     *        description="User's country code",
     *        required=true,
     *       @OA\Schema(type="string")
     *      ),
     *    @OA\Parameter(
     *        name="mobile_no",
     *      in="query",
     *       description="User's mobile number",
     *       required=true,
     *      @OA\Schema(type="string")
     *      ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="User registered successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function singup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'country_code' => 'required',
            'mobile_no' => 'required|unique:users,mobile_no',
            'password' => 'required',
            // 'gender' => 'required',
            // 'dob' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status' => false,
                'message' => $error,
                'data' => []
            ], 422);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'country_code' => $request->country_code,
                'mobile_no' => $request->mobile_no,
                'profile' => NULL,
                'gender' => $request->gender,
                // 'dob' => $request->dob,
            ]);

            $token = Str::random(30);
            User::where(['email' => $request->email])->update([
                'remember_token' => $token
            ]);

            $email = $request->email;
            $name = $request->name;
            $template = 'emails.signup';
            $subject = 'Account is created on Mandaean';
            $data = [
                'name' => $name,
                'email' => $email,
                'link' => url('verify') . '/' . $token . '?email=' . urlencode($email)
            ];
            ___mail_sender($email, $name, $template, $data, $subject);

            User::where('id', $user->id)->update(['email_verified_at' => date('Y-m-d H:i:s')]);

            DB::commit();

            return response([
                'status' => true,
                'message' => 'Signed up',
                'data' => $user
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response([
                'status' => false,
                'message' => 'Signup failed: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
    /**
     * Summary of forgot
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @OA\Post(
     *     path="/api/forgot",
     *     tags={"Auth Management"},
     *     summary="Forgot password",
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="Reset link sent to email"),
     *     @OA\Response(response="422", description="Validation errors or user not found")
     * )
     */
    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status' => false,
                'message' => $error,
                'data' => []
            ], 422);
        }

        $user = User::where(['email' => $request->email])->first();
        if ($user) {
            $token = Str::random(30);
            User::where(['email' => $request->email])->update([
                'remember_token' => $token
            ]);

            $email = $request->email;
            $name = $request->name;
            $template = 'emails.forgot';
            $subject = 'Forgot Password for Mandaean Account.';
            $data = [
                'name' => $name,
                'email' => $email,
                'link' => url('forgot-password') . '/' . $token . '?email=' . urlencode($email)
            ];
            ___mail_sender($email, $name, $template, $data, $subject);

            return response([
                'status' => true,
                'message' => 'Reset link send on mail.',
                'data' => $user
            ], 201);
        } else {
            return response([
                'status' => false,
                'message' => 'User not found.',
                'data' => []
            ], 422);
        }
    }
    /**
     * Summary of resendOTP
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @OA\Post(
     *     path="/api/resend-otp",
     *     tags={"Auth Management"},
     *     summary="Resend OTP",
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="mobile_no",
     *         in="query",
     *         description="User's mobile number",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="OTP sent successfully"),
     *     @OA\Response(response="422", description="Validation errors or user not found")
     * )
     */
    public function resendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status' => false,
                'message' => $error,
                'data' => []
            ], 422);
        }

        $user = User::where(['mobile_no' => $request->mobile_no])->first();
        if ($user) {

            $otp = rand(1111, 9999);
            $message = "Login OTP is " . $otp;

            $account_sid = env("TWILIO_SID");
            $auth_token = env("TWILIO_TOKEN");
            $twilio_number = env("TWILIO_FROM");

            $client = new Client($account_sid, $auth_token);
            try {
                $client->messages->create($request->country_code . '' . $request->mobile_no, [
                    'from' => $twilio_number,
                    'body' => $message
                ]);

                User::where('mobile_no', $request->mobile_no)->update(['otp' => $otp, 'otp_time' => date('Y-m-d H:i:s')]);

                return response([
                    'status' => true,
                    'message' => 'OTP send',
                    'data' => []
                ], 201);
            } catch (Exception $e) {
                $otp = 1111;

                User::where('mobile_no', $request->mobile_no)->update(['otp' => $otp, 'otp_time' => date('Y-m-d H:i:s')]);

                return response([
                    'status' => true,
                    'message' => 'OTP send',
                    'data' => []
                ], 201);
            }
        } else {
            return response([
                'status' => false,
                'message' => 'User not found',
                'data' => []
            ], 422);
        }
    }
    /**
     * Summary of verifyOTP
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @OA\Post(
     *     path="/api/verify-otp",
     *     tags={"Auth Management"},
     *     summary="Verify OTP",
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="mobile_no",
     *         in="query",
     *         description="User's mobile number",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="otp",
     *         in="query",
     *         description="One Time Password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="OTP verified successfully"),
     *     @OA\Response(response="422", description="OTP expired or wrong OTP entered")
     * )
     */
    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required',
            'otp' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status' => false,
                'message' => $error,
                'data' => []
            ], 422);
        }

        if (str_contains($request->mobile_no, '@')) {
            $user = User::where(['email' => $request->mobile_no])->first();
        } else {
            $user = User::where(['mobile_no' => $request->mobile_no])->first();
        }

        if ($user) {
            $token = $user->createToken('usertoken')->plainTextToken;

            $otp = $request->otp;
            if ($user->otp == $otp) {
                $currentTime = date('Y-m-d H:i:s');
                $timeDiff = getTimeDifference($user->otp_time, $currentTime);
                if ($timeDiff <= 10) {
                    User::where('id', $user->id)->update(['otp' => null, 'otp_time' => null]);
                    return response([
                        'status' => true,
                        'message' => 'OTP verified.',
                        'data' => $user,
                        'token' => $token
                    ], 422);
                } else {
                    return response([
                        'status' => false,
                        'message' => 'OTP expired. Please try again.',
                        'data' => []
                    ], 422);
                }
            } else {
                return response([
                    'status' => false,
                    'message' => 'Wrong OTP enter.',
                    'data' => []
                ], 422);
            }
        } else {
            return response([
                'status' => false,
                'message' => 'User not found',
                'data' => []
            ], 422);
        }
    }

    public function forgotPassword(Request $request, $token)
    {
        $user = User::where(['email' => $request->email, 'remember_token' => $token])->first();
        if ($user) {
            $data['title'] = 'Forgot';
            $data['id'] = base64_encode($user->id);
            $data['email'] = $user->email;
            return view('front.forgot', $data);
        } else {
            return '<h1>Token is expired or Link not Found</h1>';
        }
    }
    /**
     * Summary of updatePassword
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @OA\Post(
     *     path="/api/update-password",
     *     tags={"Auth Management"},
     *     summary="Update user password",
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="User ID (base64 encoded)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="New password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="confirm_password",
     *         in="query",
     *         description="Confirm new password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Password updated successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function updatePassword(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required|min:6|max:15|required_with:confirm_password|same:confirm_password',
                'confirm_password' => 'required|min:6|max:15',
            ],
            [
                'password.required' => 'Password field is required.',
                'password.same' => 'Password and confirm password must match.'
            ]
        );
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        $password = Hash::make($request->password);
        User::where('id', base64_decode($request->id))->update([
            'password' => $password,
            'remember_token' => NULL
        ]);
        if ($request->has("X-APY-KEY")) {
            return response([
                'status' => true,
                'message' => 'Password updated successfully.',
                'data' => []
            ], 200);
        }
        return '<h1>Password updated successfully.</h1>';
    }

    public function verify(Request $request, $token)
    {
        $user = User::where('remember_token', $token)->where('email', $request->email)->first();
        if ($user) {
            if ($user->email_verified_at) {
                return '<h1>Your account has been already verified.</h1>';
            } else {
                User::where('id', $user->id)->update(['email_verified_at' => date('Y-m-d H:i:s'), 'remember_token' => NULL]);
                return '<h1>Your account has been verified.</h1>';
            }
        } else {
            return '<h1>Token is expired or Link not Found</h1>';
        }
    }
}
