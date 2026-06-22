<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiveChatMessage;
use Illuminate\Support\Str;

class LiveChatController extends Controller
{
    public function messages(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => ['required', 'string', 'max:80'],
            'after_id' => ['nullable', 'integer', 'min:0'],
        ]);

        $messages = LiveChatMessage::query()
            ->where('conversation_id', $data['conversation_id'])
            ->when($data['after_id'] ?? null, fn ($query, $afterId) => $query->where('id', '>', $afterId))
            ->oldest()
            ->get()
            ->map(fn (LiveChatMessage $message) => $this->chatPayload($message));

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => ['nullable', 'string', 'max:80'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $conversationId = $data['conversation_id'] ?? (string) Str::uuid();

        $chat = LiveChatMessage::create([
            'conversation_id' => $conversationId,
            'sender_type' => 'customer',
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'message' => $data['message'],
            'status' => 'new',
            'is_read' => false,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message sent. An admin can reply here.',
                'conversation_id' => $conversationId,
                'data' => $this->chatPayload($chat),
            ]);
        }

        return back()->with('success', 'Thanks. We received your message and will contact you shortly.');
    }

    private function chatPayload(LiveChatMessage $message): array
    {
        return [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_type' => $message->sender_type,
            'name' => $message->name,
            'message' => $message->message,
            'created_at' => $message->created_at?->toIso8601String(),
            'display_time' => $message->created_at?->format('M d, H:i'),
        ];
    }
}
