<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveChatMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LiveChatController extends Controller
{
    public function index()
    {
        $messages = LiveChatMessage::latest()->limit(200)->get();
        $conversations = $messages
            ->groupBy(fn (LiveChatMessage $message) => $message->conversation_id ?: 'legacy-'.$message->id)
            ->map(function ($conversationMessages, $conversationId) {
                return [
                    'id' => $conversationId,
                    'latest' => $conversationMessages->sortByDesc('created_at')->first(),
                    'messages' => $conversationMessages->sortBy('created_at')->values(),
                    'unread_count' => $conversationMessages
                        ->where('sender_type', 'customer')
                        ->where('status', 'new')
                        ->count(),
                ];
            })
            ->sortByDesc(fn ($conversation) => $conversation['latest']->created_at)
            ->values();

        return view('admin.live-chat.index', [
            'conversations' => $conversations,
        ]);
    }

    public function reply(Request $request, string $conversation)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $firstMessage = LiveChatMessage::where('conversation_id', $conversation)->firstOrFail();

        LiveChatMessage::where('conversation_id', $conversation)
            ->where('sender_type', 'customer')
            ->where('status', 'new')
            ->update([
                'status' => 'read',
                'is_read' => true,
            ]);

        $reply = LiveChatMessage::create([
            'conversation_id' => $conversation,
            'sender_type' => 'admin',
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'message' => $data['message'],
            'status' => $firstMessage->status === 'closed' ? 'closed' : 'read',
            'is_read' => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reply sent.',
                'data' => $reply,
            ]);
        }

        return back()->with('success', 'Reply sent.');
    }

    public function update(Request $request, LiveChatMessage $liveChatMessage)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(LiveChatMessage::STATUSES)],
        ]);

        $liveChatMessage->update([
            'status' => $data['status'],
            'is_read' => $data['status'] !== 'new',
        ]);

        return back()->with('success', 'Chat message updated.');
    }

    public function destroy(LiveChatMessage $liveChatMessage)
    {
        $liveChatMessage->delete();

        return back()->with('success', 'Chat message deleted.');
    }
}
