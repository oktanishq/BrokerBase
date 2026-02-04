<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates the properties table matching the pgAdmin SQL script.
     * Uses single-column image storage with 'images' JSON column.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            // Primary Key
            $table->id();
            
            // Core Property Information
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('property_type', 255);
            
            // Pricing & Area
            $table->decimal('price', 12, 2)->nullable();
            $table->integer('area_sqft')->nullable();
            $table->decimal('net_price', 12, 2)->nullable();
            
            // Location
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('maps_embed_url', 500)->nullable();
            
            // Property Specifications
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(0);
            
            // Status & Marketing
            $table->string('status', 255)->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->string('label_type', 255)->default('none');
            $table->string('custom_label_color', 7)->default('#3B82F6');
            $table->integer('views_count')->default(0);
            
            // Amenities (JSON array)
            $table->json('amenities')->default('[]');
            
            // Private Vault Information
            $table->string('owner_name')->nullable();
            $table->string('owner_phone')->nullable();
            $table->text('private_notes')->nullable();
            
            // Media Management - Single Column Approach
            // Stores all images in one JSON column with metadata
            $table->json('images')->nullable();
            $table->boolean('watermark_enabled')->default(true);
            
            // User Management
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Timestamps
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('sold_at')->nullable();
            
            // Soft delete
            $table->softDeletes();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'is_featured']);
            $table->index('status');
            $table->index('property_type');
            $table->index('is_featured');
            $table->index(['latitude', 'longitude']);
            $table->index('created_at');
            $table->index('published_at');
        });

        // Add check constraints matching pgAdmin script
        DB::statement("ALTER TABLE properties ADD CONSTRAINT properties_property_type_check CHECK (property_type::text = ANY (ARRAY['apartment'::character varying, 'villa'::character varying, 'plot'::character varying, 'commercial'::character varying, 'office'::character varying]::text[]))");
        DB::statement("ALTER TABLE properties ADD CONSTRAINT properties_label_type_check CHECK (label_type::text = ANY (ARRAY['none'::character varying, 'new'::character varying, 'popular'::character varying, 'verified'::character varying, 'custom'::character varying]::text[]))");
        DB::statement("ALTER TABLE properties ADD CONSTRAINT properties_status_check CHECK (status::text = ANY (ARRAY['draft'::character varying, 'available'::character varying, 'booked'::character varying, 'sold'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
