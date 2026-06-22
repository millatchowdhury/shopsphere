@extends('layouts.admin')
@section('title', 'Live Chat')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Live Chat</h1>
</div>

<div class="live-chat-admin-list">
    @forelse($conversations as $conversation)
        @php($latest = $conversation['latest'])
        @php($visitor = $conversation['messages']->firstWhere('sender_type', 'customer') ?: $latest)
        <section class="bg-white border rounded p-4 mb-3">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-3">
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h2 class="h5 mb-0">{{ $visitor->name ?: 'Guest visitor' }}</h2>
                        @if($conversation['unread_count'])
                            <span class="badge text-bg-success">{{ $conversation['unread_count'] }} new</span>
                        @endif
                        <span class="badge text-bg-secondary status-pill">{{ $latest->status }}</span>
                    </div>
                    <div class="small text-muted">
                        @if($visitor->email) {{ $visitor->email }} @endif
                        @if($visitor->phone) <span class="ms-2">{{ $visitor->phone }}</span> @endif
                    </div>
                </div>
                <div class="small text-muted">{{ $latest->created_at->format('M d, Y H:i') }}</div>
            </div>

            <div class="admin-chat-thread mb-3">
                @foreach($conversation['messages'] as $message)
                    <div class="admin-chat-bubble {{ $message->sender_type === 'admin' ? 'admin-chat-bubble-admin' : 'admin-chat-bubble-customer' }}">
                        <div class="small text-muted mb-1">
                            {{ $message->sender_type === 'admin' ? ($message->name ?: 'Admin') : ($message->name ?: 'Guest') }}
                            <span>{{ $message->created_at->format('M d, H:i') }}</span>
                        </div>
                        <div>{{ $message->message }}</div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex flex-column flex-xl-row gap-2">
                <form method="POST" action="{{ route('admin.live-chat.reply', $conversation['id']) }}" class="flex-grow-1 d-flex gap-2">
                    @csrf
                    <input class="form-control" name="message" placeholder="Reply to this visitor" required>
                    <button class="btn btn-success">Reply</button>
                </form>
                <form method="POST" action="{{ route('admin.live-chat.update', $latest) }}" class="d-flex gap-2">
                    @csrf
                    @method('PATCH')
                    <select class="form-select" name="status" aria-label="Chat status">
                        @foreach(\App\Models\LiveChatMessage::STATUSES as $status)
                            <option value="{{ $status }}" @selected($latest->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-dark">Update</button>
                </form>
                <form method="POST" action="{{ route('admin.live-chat.destroy', $latest) }}" data-confirm="Delete latest message in this chat?">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger w-100">Delete Latest</button>
                </form>
            </div>
        </section>
    @empty
        <div class="bg-white border rounded p-4 text-center text-muted">No chat messages yet.</div>
    @endforelse
</div>
@endsection
