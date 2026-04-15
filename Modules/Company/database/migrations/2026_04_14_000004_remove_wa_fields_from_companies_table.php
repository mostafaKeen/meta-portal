<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['wa_api_key', 'wa_session_id', 'wa_provider', 'qr', 'api']);
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('wa_api_key')->nullable();
            $table->string('wa_session_id')->nullable();
            $table->string('wa_provider')->nullable();
            $table->boolean('qr')->default(false);
            $table->boolean('api')->default(false);
        });
    }
};
