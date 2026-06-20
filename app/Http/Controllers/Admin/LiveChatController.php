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
        return view('admin.live-chat.index', [
            'messages' => LiveChatMessage::latest()->paginate(20),
        ]);
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
