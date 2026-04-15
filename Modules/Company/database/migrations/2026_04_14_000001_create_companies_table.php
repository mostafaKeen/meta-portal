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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain_slug')->unique();
            $table->string('logo')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('website')->nullable();

            // Bitrix24 Integration (all nullable)
            $table->string('b24_domain')->nullable();
            $table->string('b24_client_id')->nullable();
            $table->string('b24_client_secret')->nullable();
            $table->text('b24_access_token')->nullable();
            $table->text('b24_refresh_token')->nullable();

            // WhatsApp Integration
            $table->string('wa_api_key')->nullable();
            $table->string('wa_session_id')->nullable();
            $table->string('wa_provider')->nullable();
            $table->boolean('qr')->default(false);
            $table->boolean('api')->default(false);

            // Status & Subscription
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->timestamp('trial_ends_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
