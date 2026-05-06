<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversion_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('entity_type')->index(); // LEAD or DEAL
            $table->string('entity_id')->index();
            $table->string('event_name');
            $table->json('bitrix_payload')->nullable();
            $table->json('fb_payload')->nullable();
            $table->json('fb_response')->nullable();
            $table->string('status')->index(); // success or failed
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversion_logs');
    }
};
