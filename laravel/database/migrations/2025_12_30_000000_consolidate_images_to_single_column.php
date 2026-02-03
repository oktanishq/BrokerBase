<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration to consolidate images into a single column.
 * 
 * This migration:
 * 1. Adds a new 'images' JSON column
 * 2. Migrates existing data from primary_image_path + images_metadata to single 'images' column
 * 3. Drops the old columns (optional - can be reverted in down())
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add the new 'images' column
        Schema::table('properties', function (Blueprint $table) {
            $table->json('images')->nullable()->after('primary_image_path');
        });

        // Migrate existing data
        $this->migrateExistingData();

        // Optional: Drop old columns after successful migration
        // Uncomment these lines if you're sure migration worked
        // Schema::table('properties', function (Blueprint $table) {
        //     $table->dropColumn('primary_image_path');
        // });
        // Schema::table('properties', function (Blueprint $table) {
        //     $table->dropColumn('images_metadata');
        // });
    }

    /**
     * Migrate existing data from dual-column to single-column format.
     */
    protected function migrateExistingData(): void
    {
        $properties = DB::table('properties')->get();

        foreach ($properties as $property) {
            $images = [];

            // Add primary image if exists
            if (!empty($property->primary_image_path)) {
                $images[] = [
                    'path' => $property->primary_image_path,
                    'url' => asset('storage/' . $property->primary_image_path),
                    'order' => 1,
                    'is_primary' => true,
                ];
            }

            // Add gallery images from metadata
            $metadata = json_decode($property->images_metadata ?? '[]', true);
            if (!empty($metadata) && is_array($metadata)) {
                $order = count($images) + 1;
                foreach ($metadata as $image) {
                    // Skip if this image is already the primary (to avoid duplication)
                    if (!empty($property->primary_image_path) && 
                        isset($image['path']) && 
                        $image['path'] === $property->primary_image_path) {
                        continue;
                    }

                    $images[] = [
                        'path' => $image['path'] ?? null,
                        'url' => isset($image['path']) ? asset('storage/' . $image['path']) : null,
                        'order' => $order,
                        'is_primary' => false,
                        'original_name' => $image['original_name'] ?? null,
                        'size' => $image['size'] ?? null,
                        'mime_type' => $image['mime_type'] ?? null,
                        'is_watermarked' => $image['is_watermarked'] ?? ($property->watermark_enabled ?? true),
                    ];
                    $order++;
                }
            }

            // Update the property with consolidated images
            DB::table('properties')
                ->where('id', $property->id)
                ->update(['images' => json_encode($images)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a placeholder - in production you'd need to reverse the migration
        // by extracting primary image and metadata from the consolidated column
        
        // For safety, we won't drop columns in down()
        // Manual action required to revert
    }
};
