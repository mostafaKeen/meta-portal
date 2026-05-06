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
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_chat_id')->constrained()->onDelete('cascade');
            $table->string('message_id')->unique()->nullable();
            $table->enum('direction', ['in', 'out'])->default('in');
            $table->text('text')->nullable();
            $table->string('media_type')->nullable(); // photo, voice, document, etc.
            $table->string('media_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
