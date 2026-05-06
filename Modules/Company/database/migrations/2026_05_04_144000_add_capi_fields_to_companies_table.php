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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('fb_pixel_id')->nullable()->after('b24_refresh_token');
            $table->text('fb_access_token')->nullable()->after('fb_pixel_id');
            $table->string('capi_outbound_token')->nullable()->unique()->after('fb_access_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['fb_pixel_id', 'fb_access_token', 'capi_outbound_token']);
        });
    }
};
