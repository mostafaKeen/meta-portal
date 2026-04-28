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
        Schema::dropIfExists('telegram_messages');

        Schema::create('telegram_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_id')->index();
            $table->unsignedBigInteger('telegram_chat_id')->index(); // Points to telegram_chats.id
            
            $table->enum('direction', ['in', 'out'])->index();
            $table->string('telegram_message_id')->index();
            
            $table->text('text')->nullable();
            $table->string('media_type')->default('text')->index();
            $table->string('media_path')->nullable();
            
            $table->json('metadata')->nullable();
            
            $table->softDeletes();
            $table->timestamps();

            // Idempotency check: A message ID is unique per bot
            $table->unique(['bot_id', 'telegram_message_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_messages');
    }
};
