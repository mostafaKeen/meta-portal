<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the unique constraint on message_id — WhatsApp can send duplicate
        // notifications, and the unique constraint causes crashes
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropUnique(['message_id']);
            $table->index('message_id'); // Keep a regular index for lookups
        });

        // Add composite unique index to prevent duplicate chats
        Schema::table('whatsapp_chats', function (Blueprint $table) {
            $table->unique(['whatsapp_number_id', 'chat_id'], 'whatsapp_chats_number_chat_unique');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropIndex(['message_id']);
            $table->unique('message_id');
        });

        Schema::table('whatsapp_chats', function (Blueprint $table) {
            $table->dropUnique('whatsapp_chats_number_chat_unique');
        });
    }
};
