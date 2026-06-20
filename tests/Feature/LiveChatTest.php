<?php

namespace Tests\Feature;

use App\Models\LiveChatMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_send_live_chat_message(): void
    {
        $this->postJson(route('live-chat.store'), [
            'name' => 'Shop Visitor',
            'email' => 'visitor@example.com',
            'phone' => '555-0100',
            'message' => 'I need help with my order.',
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Thanks. We received your message and will contact you shortly.');

        $this->assertDatabaseHas('live_chat_messages', [
            'name' => 'Shop Visitor',
            'email' => 'visitor@example.com',
            'phone' => '555-0100',
            'status' => 'new',
            'is_read' => false,
        ]);
    }

    public function test_visitor_can_send_live_chat_message_without_javascript(): void
    {
        $this->post(route('live-chat.store'), [
            'name' => 'No Script Visitor',
            'email' => 'noscript@example.com',
            'message' => 'The hosted chat form should still submit.',
        ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('live_chat_messages', [
            'name' => 'No Script Visitor',
            'email' => 'noscript@example.com',
            'status' => 'new',
        ]);
    }

    public function test_admin_can_view_update_and_delete_live_chat_message(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $message = LiveChatMessage::create([
            'sender_type' => 'customer',
            'name' => 'Shop Visitor',
            'email' => 'visitor@example.com',
            'message' => 'I need help with my order.',
            'status' => 'new',
            'is_read' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.live-chat.index'))
            ->assertOk()
            ->assertSee('I need help with my order.');

        $this->actingAs($admin)
            ->patch(route('admin.live-chat.update', $message), ['status' => 'read'])
            ->assertRedirect();

        $this->assertSame('read', $message->refresh()->status);
        $this->assertTrue($message->is_read);

        $this->actingAs($admin)
            ->delete(route('admin.live-chat.destroy', $message))
            ->assertRedirect();

        $this->assertDatabaseMissing('live_chat_messages', [
            'id' => $message->id,
        ]);
    }
}
