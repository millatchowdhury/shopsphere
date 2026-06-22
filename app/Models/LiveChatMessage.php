<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveChatMessage extends Model
{
    use HasFactory;

    public const STATUSES = ['new', 'read', 'closed'];

    protected $table = 'live_chat_messages';

    protected $fillable = [
        'conversation_id',
        'sender_type',
        'name',
        'email',
        'phone',
        'message',
        'status',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
