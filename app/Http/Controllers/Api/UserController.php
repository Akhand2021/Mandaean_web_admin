<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/profile",
     *     tags={"User Management"},
     *     summary="Get User Profile",
     *     description="Retrieve the authenticated user's profile data.",
     *     security={{"apiKey":{}},{"bearerAuth": {}}}, 
     *    @OA\Response(
     *        response=201,
     *       description="User profile data retrieved successfully.",
     *        @OA\JsonContent(
     *           @OA\Property(property="status", type="boolean", example=true),
     *          @OA\Property(property="message", type="string", example="User profile data."),
     *        )
     *    ),
     *   @OA\Response(
     *       response=422,
     *      description="User not found.",
     *      @OA\JsonContent(
     *          @OA\Property(property="status", type="boolean", example=false),
     *         @OA\Property(property="message", type="string", example="User not found."),
     *        @OA\Property(property="data", type="object", example={})
     *       )
     *  )
     * )
     */
    public function profile(Request $request)
    {
        $id = Auth::id();
        $user = User::find($id);

        if ($user) {
            $user->gender = ($user->gender) ? ucfirst($user->gender) : NULL;
            $user->profile = ($user->profile) ? url('/') . '/public/' . $user->profile : NULL;
            return response([
                'status' => true,
                'message' => 'User profile data.',
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
     * @OA\Post(
     *     path="/api/update-profile",
     *     tags={"User Management"},
     *     summary="Update User Profile",
     *     description="Update the authenticated user's profile data.",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "email", "country_code", "mobile_no"},
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@doe.com"),
     *                 @OA\Property(property="country_code", type="string", example="91"),
     *                 @OA\Property(property="mobile_no", type="string", example="1234567890"),
     *                 @OA\Property(property="profile", type="string", format="binary", description="Profile image file")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User profile data updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User profile data updated."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or user not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error message."),
     *             @OA\Property(property="data", type="object", example={})
     *         )
     *     )
     * )
     * @OA\Tag(name="User")
     */

    public function updateProfile(Request $request)
    {
        $id = Auth::id();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'country_code' => 'required',
            'mobile_no' => 'required|unique:users,mobile_no,' . $id,
            // 'password' => 'required',
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

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->mobile_no) {
            $user->country_code = '+' . $request->country_code;
        }
        if ($request->mobile_no) {
            $user->mobile_no = $request->mobile_no;
        }
        // $user->password = Hash::make($request->password);
        // $user->gender = $request->gender;
        // $user->dob = $request->dob;

        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $user->profile = upload_file_common($file, 'public/uploads/');
        }
        $user->save();
        $user->profile = $user->profile ?  asset($user->profile) : NULL;

        return response([
            'status' => true,
            'message' => 'User profile data updated.',
            'data' => $user
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/change-password",
     *     tags={"User Management"},
     *     summary="Change User Password",
     *     description="Change the authenticated user's password.",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="old_password", type="string", example="oldpassword123"),
     *             @OA\Property(property="new_password", type="string", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Password changed successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password changed successfully."),
     *             @OA\Property(property="data", type="object", example={})
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or user not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Old password does not match."),
     *             @OA\Property(property="data", type="object", example={})
     *         )
     *     )
     * )
     */
    public function changePassword(Request $request)
    {
        $id = Auth::id();
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status' => false,
                'message' => $error,
                'data' => []
            ], 422);
        }

        $oldPass = $request->old_password;
        $newPass = $request->new_password;
        $user = User::find($id);

        if (!$user) {
            return response([
                'status' => false,
                'message' => 'User not found.',
                'data' => []
            ], 422);
        }
        if (Hash::check($oldPass, $user->password)) {
            $user->password = Hash::make($newPass);
            $user->save();

            return response([
                'status' => true,
                'message' => 'Password changed successfully.',
                'data' => $user
            ], 201);
        } else {
            return response([
                'status' => false,
                'message' => 'Old password does not match.',
                'data' => []
            ], 422);
        }
    }
    /**
     * Delete user account
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @OA\Delete(
     *     path="/api/delete-account",
     *     tags={"User Management"},
     *     summary="Delete User Account",
     *     description="Delete the authenticated user's account.",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=201,
     *         description="Account deleted successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Account Deleted."),
     *             @OA\Property(property="data", type="object", example={})
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="User not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found."),
     *             @OA\Property(property="data", type="object", example={})
     *         )
     *     )
     * )
     * @OA\Tag(name="User")
     */
    public function deleteAccount(Request $request)
    {
        $id = Auth::id();
        $user = User::where(['id' => $id])->first();
        if ($user) {
            User::where('email', $user->email)->delete();
            return response([
                'status' => true,
                'message' => 'Account Deleted.',
                'data' => []
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
     * @OA\Post(
     *    path="/api/fcm-token",
     *   tags={"User Management"},
     *    summary="Save FCM Token",
     *    description="Save the user's FCM token for push notifications.",
     *    security={{"apiKey":{}},{"bearerAuth": {}}},
     *    @OA\RequestBody(
     *        required=true,
     *       @OA\JsonContent(
     *           @OA\Property(property="fcm_token", type="string", example="your_fcm_token_here")
     *       )
     *    ),
     *   @OA\Response(
     *       response=200,
     *      description="FCM Token saved successfully.",
     *      @OA\JsonContent(
     *           @OA\Property(property="message", type="string", example="FCM Token saved.")
     *       )
     *    ),
     *    @OA\Response(
     *        response=422,
     *        description="Validation error.",
     *        @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="The fcm token field is required.")
     *        )
     *    )
     * )
     */
    public function saveToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = auth()->user(); // or use ID
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json(['message' => 'FCM Token saved.']);
    }
}
