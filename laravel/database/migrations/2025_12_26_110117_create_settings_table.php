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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('agency_name')->default('Elite Homes Real Estate');
            $table->string('rera_id')->default('ORN-882910');
            $table->string('w_no')->default('+91 50 123 4567');
            $table->text('office_address')->default('Office 402, Business Bay Tower, Dubai, UAE');
            $table->string('logo_url')->nullable();
            $table->string('theme_color')->default('blue');
            $table->string('custom_color')->default('1E3A8A');
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
