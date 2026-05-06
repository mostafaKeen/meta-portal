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
            $table->renameColumn('whatsapp_session_id', 'whatsapp_number_id');
        });
    }

    public function down(): void 
    {
        Schema::table('whatsapp_chats', function (Blueprint $table) {
            $table->renameColumn('whatsapp_number_id', 'whatsapp_session_id');
        });
    }
};
