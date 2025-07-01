<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FriendSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/friends",
     *     tags={"Community"},
     *     summary="Get all friend suggestions for the authenticated user",
     *     security={{"apiKey":{}},{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of sent and received friend suggestions."
     *     )
     * )
     */
    public function index()
    {
        $user = Auth::user();
        $sent = $user->friendSuggestionsSent()->with('suggestedFriend')->get();
        $received = $user->friendSuggestionsReceived()->with('user')->get();
        return response()->json(['sent' => $sent, 'received' => $received]);
    }

    /**
     * @OA\Post(
     *     path="/api/friends",
     *     tags={"Community"},
     *     summary="Send a friend suggestion",
     *     security={{"apiKey":{}},{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"suggested_friend_id"},
     *             @OA\Property(property="suggested_friend_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Friend suggestion sent."
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'suggested_friend_id' => 'required|exists:users,id'
        ]);
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';
        $suggestion = FriendSuggestion::create($data);
        return response()->json($suggestion, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/friends/{friendSuggestion}",
     *     tags={"Community"},
     *     summary="Update the status of a friend suggestion",
     *     security={{"apiKey":{}},{"bearerAuth":{}}},
     *     @OA\Parameter(name="friendSuggestion", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"pending","accepted","rejected","suggested"}, example="accepted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Friend suggestion updated."
     *     )
     * )
     */
    public function update(Request $request, FriendSuggestion $friendSuggestion)
    {
        $this->authorize('update', $friendSuggestion);
        $data = $request->validate([
            'status' => 'required|in:pending,accepted,rejected,suggested'
        ]);
        $friendSuggestion->update($data);
        return response()->json($friendSuggestion);
    }

    /**
     * @OA\Delete(
     *     path="/api/friends/{friendSuggestion}",
     *     tags={"Community"},
     *     summary="Delete a friend suggestion or relationship",
     *     security={{"apiKey":{}},{"bearerAuth":{}}},
     *     @OA\Parameter(name="friendSuggestion", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Friend suggestion deleted."
     *     )
     * )
     */
    public function destroy(FriendSuggestion $friendSuggestion)
    {
        $this->authorize('delete', $friendSuggestion);
        $friendSuggestion->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
