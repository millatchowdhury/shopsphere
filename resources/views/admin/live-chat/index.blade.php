@extends('layouts.admin')
@section('title', 'Live Chat Messages')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Live Chat Messages</h1>
</div>

<div class="bg-white border rounded p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Visitor</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Received</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $message->name ?: 'Guest visitor' }}</div>
                            @if($message->email)
                                <div class="small text-muted">{{ $message->email }}</div>
                            @endif
                            @if($message->phone)
                                <div class="small text-muted">{{ $message->phone }}</div>
                            @endif
                        </td>
                        <td style="max-width: 420px;">
                            <div class="text-wrap">{{ $message->message }}</div>
                        </td>
                        <td><span class="badge text-bg-secondary status-pill">{{ $message->status }}</span></td>
                        <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.live-chat.update', $message) }}" class="d-inline-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select class="form-select form-select-sm" name="status" aria-label="Chat status">
                                    @foreach(\App\Models\LiveChatMessage::STATUSES as $status)
                                        <option value="{{ $status }}" @selected($message->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-outline-dark">Update</button>
                            </form>
                            <form method="POST" action="{{ route('admin.live-chat.destroy', $message) }}" class="d-inline" data-confirm="Delete this chat message?">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No chat messages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="shop-pagination mt-3">
        {{ $messages->links() }}
    </div>
</div>
@endsection
