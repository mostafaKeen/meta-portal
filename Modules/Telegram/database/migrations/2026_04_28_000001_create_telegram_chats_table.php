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
        Schema::dropIfExists('telegram_chats');
        
        Schema::create('telegram_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_id')->index();
            $table->string('chat_id')->index(); // Telegram Chat ID
            
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('photo_url')->nullable();
            
            $table->timestamp('last_message_at')->nullable()->index();
            
            $table->softDeletes();
            $table->timestamps();

            // Unique constraint: A user can belong to multiple bots, but only once per bot
            $table->unique(['chat_id', 'bot_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_chats');
    }
};
