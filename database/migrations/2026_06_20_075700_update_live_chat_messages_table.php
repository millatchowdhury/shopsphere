<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('live_chat_messages')) {
            return;
        }

        Schema::table('live_chat_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('live_chat_messages', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }

            if (!Schema::hasColumn('live_chat_messages', 'status')) {
                $table->string('status')->default('new')->index()->after('message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('live_chat_messages')) {
            return;
        }

        Schema::table('live_chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('live_chat_messages', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('live_chat_messages', 'phone')) {
                $table->dropColumn('phone');
            }
        });
    }
};
