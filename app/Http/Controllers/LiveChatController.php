<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiveChatMessage;

class LiveChatController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $chat = LiveChatMessage::create([
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
                'message' => 'Thanks. We received your message and will contact you shortly.',
                'data' => $chat,
            ]);
        }

        return back()->with('success', 'Thanks. We received your message and will contact you shortly.');
    }
}
