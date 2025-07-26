<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * @OA\GET(
     *     path="/api/faqs",
     *     tags={"Faqs"},
     *     summary="Get all faq for the authenticated user",
     *     security={{"apiKey":{}},{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List all Faqs"
     *     )
     * )
     */
    public function index()
    {
        $faqs = Faq::where(['is_active' => true])->get();

        return response()->json([
            'faqs' => $faqs
        ]);
    }
}
