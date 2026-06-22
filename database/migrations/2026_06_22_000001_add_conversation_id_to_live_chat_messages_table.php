<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('live_chat_messages')) {
            return;
        }

        Schema::table('live_chat_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('live_chat_messages', 'conversation_id')) {
                $table->string('conversation_id', 80)->nullable()->index()->after('id');
            }
        });

        DB::table('live_chat_messages')
            ->whereNull('conversation_id')
            ->orderBy('id')
            ->get(['id'])
            ->each(function ($message) {
                DB::table('live_chat_messages')
                    ->where('id', $message->id)
                    ->update(['conversation_id' => 'legacy-'.$message->id]);
            });
    }

    public function down(): void
    {
        if (! Schema::hasTable('live_chat_messages')) {
            return;
        }

        Schema::table('live_chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('live_chat_messages', 'conversation_id')) {
                $table->dropColumn('conversation_id');
            }
        });
    }
};
