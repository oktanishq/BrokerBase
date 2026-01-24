<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            // Primary Key
            $table->id();
            
            // Core Property Information
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('property_type', ['apartment', 'villa', 'plot', 'commercial', 'office']);
            
            // Pricing & Area
            $table->decimal('price', 12, 2)->nullable();
            $table->integer('area_sqft')->nullable();
            $table->decimal('net_price', 12, 2)->nullable();
            
            // Location
            $table->text('address')->nullable(); // Made optional to match frontend
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('maps_embed_url', 500)->nullable();
            
            // Property Specifications
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(0);
            
            // Status & Marketing
            $table->enum('status', ['draft', 'available', 'booked', 'sold'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->enum('label_type', ['none', 'new', 'popular', 'verified', 'custom'])->default('none');
            $table->string('custom_label_color', 7)->default('#3B82F6');
            $table->integer('views_count')->default(0);
            
            // Amenities (JSON array)
            $table->json('amenities')->default('[]');
            
            // Private Vault Information
            $table->string('owner_name')->nullable();
            $table->string('owner_phone')->nullable();
            $table->text('private_notes')->nullable();
            
            // Media Management
            $table->string('primary_image_path')->nullable();
            $table->json('images_metadata')->default('[]');
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

        // Add check constraints to match PostgreSQL schema
        DB::statement("ALTER TABLE properties ADD CONSTRAINT properties_property_type_check CHECK (property_type::text = ANY (ARRAY['apartment'::character varying, 'villa'::character varying, 'plot'::character varying, 'commercial'::character varying, 'office'::character varying]::text[]))");
        DB::statement("ALTER TABLE properties ADD CONSTRAINT properties_label_type_check CHECK (label_type::text = ANY (ARRAY['none'::character varying, 'new'::character varying, 'popular'::character varying, 'verified'::character varying, 'custom'::character varying]::text[]))");
        DB::statement("ALTER TABLE properties ADD CONSTRAINT properties_status_check CHECK (status::text = ANY (ARRAY['draft'::character varying, 'available'::character varying, 'booked'::character varying, 'sold'::character varying]::text[]))");
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};