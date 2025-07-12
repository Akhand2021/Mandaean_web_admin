<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/inquiry-now",
     *     tags={"Inquiry"},
     *     summary="Submit an inquiry",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "mobile", "ask_query"},
     *             @OA\Property(property="product_id", type="integer", example=123, description="Optional product ID"),
     *             @OA\Property(property="name", type="string", example="John Doe", description="Name of the person making the inquiry"),
     *            @OA\Property(property="email", type="string", format="email", example="john@example.com", description="Email address of the person making the inquiry"),
     *            @OA\Property(property="mobile", type="string", example="1234567890", description="Mobile number of the person making the inquiry"),
     *            @OA\Property(property="ask_query", type="string", example="I have a question about this product.", description="The inquiry question or message")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Inquiry submitted successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true, description="Indicates if the inquiry was submitted successfully"),
     *             @OA\Property(property="message", type="string", example="Your query submitted.", description="Success message"),
     *             @OA\Property(property="data", type="object", description="Inquiry data", ),
     *            @OA\Property(property="data.name", type="string", example="John Doe", description="Name of the person making the inquiry"),
     *            @OA\Property(property="data.email", type="string", format="email", example="john@example.com", description="Email address of the person making the inquiry"),
     *          )
     *     ),
     *     @OA\Response(
     *        response=422,
     *        description="Validation error",
     *       @OA\JsonContent(
     *           @OA\Property(property="status", type="boolean", example=false, description="Indicates if the inquiry submission failed"),
     *          @OA\Property(property="message", type="string", example="Validation error message", description="Error message"),
     *          @OA\Property(property="data", type="array", @OA\Items(type="string"), description="Validation errors")
     *        )
     *    ),
     * )
     * 
     *              
     */
    public function InquiryNow(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'nullable', // changed from 'required' to 'nullable'
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'ask_query' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status' => false,
                'message' => $error,
                'data' => []
            ], 422);
        }

        $id = Auth::id();

        $data = Inquiry::create([
            'user_id' => $id,
            'product_id' => $request->product_id, // can be null now
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'query' => $request->ask_query,
            'status' => 'pending'
        ]);

        $email = $request->email;
        $name = $request->name;
        $template = 'emails.inquiry';
        $subject = 'Ask for query | Mandaean';
        $data = [
            'name' => $name,
            'email' => $email,
            'query' => $request->ask_query
        ];
        ___mail_sender('info@mandaean.world', $name, $template, $data, $subject);

        return response([
            'status' => true,
            'message' => 'Your query submitted.',
            'data' => $data
        ], 201);
    }
}
