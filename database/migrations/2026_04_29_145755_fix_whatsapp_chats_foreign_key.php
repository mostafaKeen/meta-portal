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
        Schema::table('whatsapp_chats', function (Blueprint $table) {
            // Drop the old FK that points to whatsapp_sessions
            $table->dropForeign('whatsapp_chats_whatsapp_session_id_foreign');
            // Add the correct FK pointing to whatsapp_numbers
            $table->foreign('whatsapp_number_id')->references('id')->on('whatsapp_numbers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_chats', function (Blueprint $table) {
            $table->dropForeign(['whatsapp_number_id']);
        });
    }
};
