<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * @OA\Get(
     *     path="/chat/active-users",
     *     summary="Get active users for chat",
     *     tags={"Chat"},
     *     security={{"apiKey":{}}, {"bearer_token":{}}},
     *     @OA\Response(response=200, description="List of active users")
     * )
     */
    // Get active users (users who have sent/received messages recently)
    public function activeUsers(Request $request)
    {
        $userId = Auth::id();
        // Get IDs of users blocked by or who have blocked the current user
        $blockedIds = Block::where('user_id', $userId)
            ->pluck('blocked_user_id')
            ->merge(Block::where('blocked_user_id', $userId)->pluck('user_id'));
        $activeThreshold = now()->subMinutes(5);
        $users = User::where('id', '!=', $userId)
            ->where('last_seen', '>=', $activeThreshold)
            ->whereNotIn('id', $blockedIds)
            ->whereHas('messages', function ($q) use ($userId) {
                $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
            })
            ->get();
        return response()->json($users);
    }

    /**
     * @OA\Get(
     *     path="/chat/last-seen/{userId}",
     *     summary="Get last seen for a user",
     *     tags={"Chat"},
     *     security={{"apiKey":{}}, {"bearer_token":{}}},
     *     @OA\Parameter(name="userId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Last seen timestamp")
     * )
     */
    // Get last seen for a user
    public function lastSeen($userId)
    {
        $user = User::findOrFail($userId);
        return response()->json(['last_seen' => $user->last_seen]);
    }

    /**
     * @OA\Post(
     *     path="/chat/send",
     *     summary="Send a chat message",
     *     tags={"Chat"},
     *     security={{"apiKey":{}}, {"bearer_token":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="receiver_id", type="integer"),
     *                 @OA\Property(property="message", type="string"),
     *                 @OA\Property(property="attachment", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Message sent")
     * )
     */
    // Send a message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'attachment' => 'nullable|file',
        ]);
        $data = [
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'status' => 'sent',
        ];
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }
        $message = Message::create($data);
        broadcast(new MessageSent($message))->toOthers();
        return response()->json($message);
    }

    /**
     * @OA\Post(
     *     path="/chat/mark-delivered",
     *     summary="Mark messages as delivered",
     *     tags={"Chat"},
     *     security={{"apiKey":{}}, {"bearer_token":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="message_ids", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=200, description="Messages marked as delivered")
     * )
     */
    // Mark messages as delivered
    public function markDelivered(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
        ]);
        Message::whereIn('id', $request->message_ids)
            ->update(['status' => 'delivered', 'delivered_at' => now()]);
        return response()->json(['status' => 'delivered']);
    }

    /**
     * @OA\Get(
     *     path="/chat/history/{userId}",
     *     summary="Get paginated chat history with a user",
     *     tags={"Chat"},
     *     security={{"apiKey":{}}, {"bearer_token":{}}},
     *     @OA\Parameter(name="userId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Paginated chat history")
     * )
     */
    // Get chat history (paginated)
    public function chatHistory(Request $request, $userId)
    {
        $authId = Auth::id();
        $perPage = $request->get('per_page', 20);
        $messages = Message::where(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $authId)->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $authId);
        })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        return response()->json($messages);
    }

    /**
     * @OA\Post(
     *     path="/chat/delete",
     *     summary="Bulk delete chat messages",
     *     tags={"Chat"},
     *     security={{"apiKey":{}}, {"bearer_token":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="message_ids", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=200, description="Messages deleted")
     * )
     */
    // Bulk delete messages
    public function deleteMessages(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
        ]);
        Message::whereIn('id', $request->message_ids)->delete();
        return response()->json(['status' => 'deleted']);
    }

    /**
     * Block a user from chatting
     *
     * @OA\Post(
     *     path="/chat/block",
     *     summary="Block a user from chat",
     *     tags={"Chat"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="blocked_user_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User blocked")
     * )
     */
    public function blockUser(Request $request)
    {
        $request->validate([
            'blocked_user_id' => 'required|exists:users,id',
        ]);
        $userId = Auth::id();
        if ($userId == $request->blocked_user_id) {
            return response()->json(['error' => 'You cannot block yourself.'], 422);
        }
        $block = Block::firstOrCreate([
            'user_id' => $userId,
            'blocked_user_id' => $request->blocked_user_id,
        ]);
        return response()->json(['status' => 'blocked', 'block' => $block]);
    }

    /**
     * Unblock a user from chatting
     *
     * @OA\Post(
     *     path="/chat/unblock",
     *     summary="Unblock a user from chat",
     *     tags={"Chat"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="blocked_user_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User unblocked")
     * )
     */
    public function unblockUser(Request $request)
    {
        $request->validate([
            'blocked_user_id' => 'required|exists:users,id',
        ]);
        $userId = Auth::id();
        Block::where('user_id', $userId)
            ->where('blocked_user_id', $request->blocked_user_id)
            ->delete();
        return response()->json(['status' => 'unblocked']);
    }
}
