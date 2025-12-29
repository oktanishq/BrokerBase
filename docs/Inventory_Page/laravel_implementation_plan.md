# Laravel Implementation Plan - Property Management System

## Overview
Implementation guide for integrating the property database schema with Laravel's MVC architecture.

## 1. Laravel Migration File

### File: `database/migrations/2025_12_28_000000_create_properties_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
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
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
```

## 2. Eloquent Model Structure

### File: `app/Models/Property.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'property_type',
        'price',
        'area_sqft',
        'net_price',
        'address',
        'latitude',
        'longitude',
        'bedrooms',
        'bathrooms',
        'status',
        'is_featured',
        'label_type',
        'custom_label_color',
        'views_count',
        'amenities',
        'owner_name',
        'owner_phone',
        'private_notes',
        'primary_image_path',
        'images_metadata',
        'watermark_enabled',
        'user_id',
        'published_at',
        'sold_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'net_price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'is_featured' => 'boolean',
        'watermark_enabled' => 'boolean',
        'views_count' => 'integer',
        'amenities' => 'array',
        'images_metadata' => 'array',
        'published_at' => 'datetime',
        'sold_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $dates = [
        'published_at',
        'sold_at',
        'deleted_at',
    ];

    /**
     * Constants for property types
     */
    const PROPERTY_TYPES = [
        'apartment' => 'Apartment',
        'villa' => 'Villa',
        'plot' => 'Plot',
        'commercial' => 'Commercial',
        'office' => 'Office',
    ];

    /**
     * Constants for status
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_AVAILABLE = 'available';
    const STATUS_BOOKED = 'booked';
    const STATUS_SOLD = 'sold';

    /**
     * Constants for label types
     */
    const LABEL_NONE = 'none';
    const LABEL_NEW = 'new';
    const LABEL_POPULAR = 'popular';
    const LABEL_VERIFIED = 'verified';
    const LABEL_CUSTOM = 'custom';

    /**
     * Get the user that owns the property
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all images with metadata
     */
    public function getAllImagesAttribute(): array
    {
        $images = [];
        
        // Add primary image if exists
        if ($this->primary_image_path) {
            $images[] = [
                'path' => $this->primary_image_path,
                'type' => 'primary',
                'is_primary' => true,
            ];
        }
        
        // Add gallery images
        if ($this->images_metadata) {
            foreach ($this->images_metadata as $image) {
                $images[] = [
                    'path' => $image['path'],
                    'type' => 'gallery',
                    'is_primary' => false,
                    'original_name' => $image['original_name'] ?? null,
                    'size' => $image['size'] ?? null,
                    'mime_type' => $image['mime_type'] ?? null,
                    'order' => $image['order'] ?? 0,
                    'is_watermarked' => $image['is_watermarked'] ?? false,
                ];
            }
        }
        
        return $images;
    }

    /**
     * Get primary image URL
     */
    public function getPrimaryImageUrlAttribute(): ?string
    {
        if (!$this->primary_image_path) {
            return null;
        }
        
        return asset('storage/' . $this->primary_image_path);
    }

    /**
     * Scope for available properties
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope for featured properties
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                    ->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope for user properties
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for properties by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('property_type', $type);
    }

    /**
     * Scope for properties by status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Mark as published
     */
    public function publish(): void
    {
        $this->update([
            'status' => self::STATUS_AVAILABLE,
            'published_at' => now(),
        ]);
    }

    /**
     * Mark as sold
     */
    public function markAsSold(): void
    {
        $this->update([
            'status' => self::STATUS_SOLD,
            'sold_at' => now(),
        ]);
    }

    /**
     * Save images metadata
     */
    public function saveImages(array $images, bool $watermarkEnabled = true): void
    {
        $metadata = [];
        
        foreach ($images as $index => $image) {
            $metadata[] = [
                'path' => $image['path'],
                'original_name' => $image['original_name'] ?? null,
                'size' => $image['size'] ?? null,
                'mime_type' => $image['mime_type'] ?? null,
                'order' => $index + 1,
                'is_watermarked' => $watermarkEnabled,
            ];
        }
        
        $this->update([
            'images_metadata' => $metadata,
            'watermark_enabled' => $watermarkEnabled,
        ]);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        if (!$this->price) {
            return 'Price TBD';
        }
        
        return '$' . number_format($this->price, 0);
    }

    /**
     * Get formatted area
     */
    public function getFormattedAreaAttribute(): string
    {
        if (!$this->area_sqft) {
            return 'N/A';
        }
        
        return number_format($this->area_sqft) . ' sqft';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'bg-green-100 text-green-700 border-green-200',
            self::STATUS_SOLD => 'bg-red-100 text-red-700 border-red-200',
            self::STATUS_BOOKED => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            self::STATUS_DRAFT => 'bg-gray-200 text-gray-600 border-gray-300',
            default => 'bg-gray-100 text-gray-600 border-gray-200',
        };
    }

    /**
     * Get label badge data
     */
    public function getLabelBadgeAttribute(): array
    {
        if ($this->label_type === 'none') {
            return null;
        }
        
        $labels = [
            'new' => ['label' => 'New Arrival', 'color' => '#3B82F6'],
            'popular' => ['label' => 'Popular', 'color' => '#F59E0B'],
            'verified' => ['label' => 'Verified', 'color' => '#10B981'],
            'custom' => ['label' => 'Custom', 'color' => $this->custom_label_color],
        ];
        
        return $labels[$this->label_type] ?? null;
    }
}
```

## 3. Controller Integration

### File: `app/Http/Controllers/Admin/PropertyController.php` (Enhanced)

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties
     */
    public function index(Request $request): JsonResponse
    {
        $query = Property::with('user:id,name')
            ->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->type, function ($q) use ($request) {
                return $q->where('property_type', $request->type);
            })
            ->when($request->search, function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    $query->where('title', 'ilike', "%{$request->search}%")
                          ->orWhere('address', 'ilike', "%{$request->search}%");
                });
            });

        $properties = $query->orderBy('created_at', 'desc')
                           ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $properties->items(),
            'pagination' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'per_page' => $properties->perPage(),
                'total' => $properties->total(),
            ]
        ]);
    }

    /**
     * Store a newly created property
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|in:apartment,villa,plot,commercial,office',
            'price' => 'nullable|numeric|min:0',
            'area_sqft' => 'nullable|integer|min:0',
            'net_price' => 'nullable|numeric|min:0',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'amenities' => 'nullable|array',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:50',
            'private_notes' => 'nullable|string',
            'status' => 'nullable|in:draft,available,booked,sold',
            'is_featured' => 'nullable|boolean',
            'label_type' => 'nullable|in:none,new,popular,verified,custom',
            'custom_label_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'watermark_enabled' => 'nullable|boolean',
        ]);

        try {
            $property = Property::create([
                ...$validated,
                'user_id' => auth()->id(),
                'status' => $validated['status'] ?? 'draft',
                'is_featured' => $validated['is_featured'] ?? false,
                'label_type' => $validated['label_type'] ?? 'none',
                'custom_label_color' => $validated['custom_label_color'] ?? '#3B82F6',
                'watermark_enabled' => $validated['watermark_enabled'] ?? true,
                'amenities' => $validated['amenities'] ?? [],
            ]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                $this->handleImageUploads($property, $request->file('images'));
            }

            return response()->json([
                'success' => true,
                'message' => 'Property created successfully',
                'data' => $property->load('user:id,name')
            ], 201);

        } catch (\Exception $e) {
            Log::error('Property creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create property'
            ], 500);
        }
    }

    /**
     * Display the specified property
     */
    public function show(Property $property): JsonResponse
    {
        // Increment view count
        $property->incrementViews();
        
        return response()->json([
            'success' => true,
            'data' => $property->load('user:id,name')
        ]);
    }

    /**
     * Update the specified property
     */
    public function update(Request $request, Property $property): JsonResponse
    {
        $this->authorize('update', $property);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'sometimes|required|in:apartment,villa,plot,commercial,office',
            'price' => 'nullable|numeric|min:0',
            'area_sqft' => 'nullable|integer|min:0',
            'net_price' => 'nullable|numeric|min:0',
            'address' => 'sometimes|required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'status' => 'nullable|in:draft,available,booked,sold',
            'is_featured' => 'nullable|boolean',
            'label_type' => 'nullable|in:none,new,popular,verified,custom',
            'custom_label_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'amenities' => 'nullable|array',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:50',
            'private_notes' => 'nullable|string',
            'watermark_enabled' => 'nullable|boolean',
        ]);

        try {
            $property->update($validated);

            // Handle status transitions
            if (isset($validated['status'])) {
                $this->handleStatusTransition($property, $validated['status']);
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                $this->handleImageUploads($property, $request->file('images'));
            }

            return response()->json([
                'success' => true,
                'message' => 'Property updated successfully',
                'data' => $property->fresh()->load('user:id,name')
            ]);

        } catch (\Exception $e) {
            Log::error('Property update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update property'
            ], 500);
        }
    }

    /**
     * Remove the specified property
     */
    public function destroy(Property $property): JsonResponse
    {
        $this->authorize('delete', $property);

        try {
            // Delete associated images
            $this->deletePropertyImages($property);
            
            $property->delete();

            return response()->json([
                'success' => true,
                'message' => 'Property deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Property deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete property'
            ], 500);
        }
    }

    /**
     * Handle image uploads
     */
    private function handleImageUploads(Property $property, array $images): void
    {
        $userId = auth()->id();
        $propertyId = $property->id;
        $imageMetadata = [];

        foreach ($images as $index => $image) {
            $originalName = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();
            $fileName = Str::uuid() . '.' . $extension;
            
            // Create directory structure
            $directory = "properties/{$userId}/{$propertyId}/" . ($index === 0 ? 'primary' : 'gallery');
            
            // Store image
            $path = $image->storeAs($directory, $fileName, 'public');
            
            // Add watermark if enabled
            if ($property->watermark_enabled) {
                $this->applyWatermark($path);
            }
            
            // Store metadata
            $imageMetadata[] = [
                'path' => $path,
                'original_name' => $originalName,
                'size' => $image->getSize(),
                'mime_type' => $image->getMimeType(),
                'order' => $index + 1,
                'is_watermarked' => $property->watermark_enabled,
            ];
            
            // Set first image as primary
            if ($index === 0 && !$property->primary_image_path) {
                $property->update(['primary_image_path' => $path]);
            }
        }
        
        // Update gallery images
        $property->update(['images_metadata' => $imageMetadata]);
    }

    /**
     * Apply watermark to image
     */
    private function applyWatermark(string $imagePath): void
    {
        try {
            $fullPath = Storage::disk('public')->path($imagePath);
            $image = Image::make($fullPath);
            
            // Add simple text watermark
            $image->text('Elite Homes', $image->width() - 20, $image->height() - 20, function($font) {
                $font->file(public_path('fonts/Arial.ttf'));
                $font->size(24);
                $font->color('#ffffff');
                $font->align('right');
                $font->valign('bottom');
            });
            
            $image->save($fullPath);
        } catch (\Exception $e) {
            Log::warning('Watermark application failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete all property images
     */
    private function deletePropertyImages(Property $property): void
    {
        // Delete primary image
        if ($property->primary_image_path) {
            Storage::disk('public')->delete($property->primary_image_path);
        }
        
        // Delete gallery images
        if ($property->images_metadata) {
            foreach ($property->images_metadata as $image) {
                Storage::disk('public')->delete($image['path']);
            }
        }
    }

    /**
     * Handle status transitions
     */
    private function handleStatusTransition(Property $property, string $newStatus): void
    {
        match($newStatus) {
            Property::STATUS_AVAILABLE => $property->publish(),
            Property::STATUS_SOLD => $property->markAsSold(),
            default => null,
        };
    }
}
```

## 4. Image Upload Handling

### File: `app/Services/ImageUploadService.php`

```php
<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageUploadService
{
    /**
     * Upload multiple images for a property
     */
    public function uploadPropertyImages(UploadedFile $image, int $userId, int $propertyId, int $order = 1): array
    {
        $originalName = $image->getClientOriginalName();
        $extension = $image->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        
        // Determine directory based on order
        $directory = $order === 1 ? 'primary' : 'gallery';
        $fullDirectory = "properties/{$userId}/{$propertyId}/{$directory}";
        
        // Create directory if not exists
        Storage::disk('public')->makeDirectory($fullDirectory);
        
        // Store original image
        $path = $image->storeAs($fullDirectory, $fileName, 'public');
        
        // Generate different sizes if needed
        $this->generateImageSizes($path);
        
        return [
            'path' => $path,
            'original_name' => $originalName,
            'size' => $image->getSize(),
            'mime_type' => $image->getMimeType(),
            'order' => $order,
        ];
    }

    /**
     * Generate different image sizes
     */
    private function generateImageSizes(string $originalPath): void
    {
        $sizes = [
            'thumbnail' => [300, 200],
            'medium' => [800, 600],
            'large' => [1200, 800],
        ];
        
        $fullPath = Storage::disk('public')->path($originalPath);
        
        foreach ($sizes as $sizeName => [$width, $height]) {
            $this->createResizedImage($fullPath, $width, $height, $sizeName);
        }
    }

    /**
     * Create resized version of image
     */
    private function createResizedImage(string $originalPath, int $width, int $height, string $sizeName): void
    {
        try {
            $image = Image::make($originalPath);
            $image->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            });
            
            // Generate new path for resized image
            $pathInfo = pathinfo($originalPath);
            $resizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . "_{$sizeName}." . $pathInfo['extension'];
            
            $image->save($resizedPath);
        } catch (\Exception $e) {
            Log::warning("Failed to create {$sizeName} image: " . $e->getMessage());
        }
    }

    /**
     * Apply watermark to image
     */
    public function applyWatermark(string $imagePath, string $watermarkText = 'Elite Homes'): bool
    {
        try {
            $fullPath = Storage::disk('public')->path($imagePath);
            $image = Image::make($fullPath);
            
            // Add watermark
            $image->text($watermarkText, $image->width() - 20, $image->height() - 20, function($font) {
                $font->file(public_path('fonts/Arial-Bold.ttf'));
                $font->size(20);
                $font->color('#ffffff');
                $font->align('right');
                $font->valign('bottom');
                $font->angle(-45);
            });
            
            $image->save($fullPath);
            return true;
        } catch (\Exception $e) {
            Log::error('Watermark application failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete property images
     */
    public function deletePropertyImages(array $imagePaths): bool
    {
        try {
            foreach ($imagePaths as $path) {
                // Delete original and resized versions
                $pathInfo = pathinfo($path);
                $basePath = $pathInfo['dirname'] . '/' . $pathInfo['filename'];
                
                Storage::disk('public')->delete($path);
                
                // Delete resized versions
                $sizes = ['thumbnail', 'medium', 'large'];
                foreach ($sizes as $size) {
                    $resizedPath = "{$basePath}_{$size}.{$pathInfo['extension']}";
                    Storage::disk('public')->delete($resizedPath);
                }
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Image deletion failed: ' . $e->getMessage());
            return false;
        }
    }
}
```

## 5. Frontend Integration

### Alpine.js Data Structure Mapping

```javascript
// Frontend form data structure
formData: {
    title: '',           // → properties.title
    type: 'apartment',   // → properties.property_type
    price: '',          // → properties.price
    area: '',           // → properties.area_sqft
    bedrooms: 3,        // → properties.bedrooms
    bathrooms: 2,       // → properties.bathrooms
    description: '',    // → properties.description
    amenities: [],      // → properties.amenities
    address: '',        // → properties.address
    latitude: '',       // → properties.latitude
    longitude: '',      // → properties.longitude
    images: [],         // → properties.images_metadata
    watermark: true,    // → properties.watermark_enabled
    ownerName: '',      // → properties.owner_name
    ownerPhone: '',     // → properties.owner_phone
    netPrice: '',       // → properties.net_price
    privateNotes: ''    // → properties.private_notes
}

// Edit modal data structure
modalData: {
    status: 'available',    // → properties.status
    isFeatured: false,      // → properties.is_featured
    label: 'none',          // → properties.label_type
    customLabelColor: '#3B82F6'  // → properties.custom_label_color
}
```

## 6. API Endpoints Structure

### RESTful API Routes
```php
Route::middleware('auth:sanctum')->group(function () {
    // Property CRUD operations
    Route::get('/admin/properties', [PropertyController::class, 'index']);
    Route::post('/admin/properties', [PropertyController::class, 'store']);
    Route::get('/admin/properties/{property}', [PropertyController::class, 'show']);
    Route::put('/admin/properties/{property}', [PropertyController::class, 'update']);
    Route::delete('/admin/properties/{property}', [PropertyController::class, 'destroy']);
    
    // Property-specific actions
    Route::post('/admin/properties/{property}/publish', [PropertyController::class, 'publish']);
    Route::post('/admin/properties/{property}/mark-sold', [PropertyController::class, 'markSold']);
    Route::post('/admin/properties/{property}/images', [PropertyController::class, 'uploadImages']);
    Route::delete('/admin/properties/{property}/images/{imageId}', [PropertyController::class, 'deleteImage']);
});
```

## Implementation Steps

1. **Create Migration**: Run `php artisan make:migration create_properties_table`
2. **Generate Model**: Run `php artisan make:model Property`
3. **Create Controller**: Run `php artisan make:controller Admin/PropertyController`
4. **Update Routes**: Add RESTful routes to `routes/api.php`
5. **Create Image Service**: Implement image upload and management
6. **Update Frontend**: Connect Alpine.js forms to API endpoints
7. **Test Integration**: Verify full data flow from frontend to database

## Next Steps

- [ ] Create Laravel migration file
- [ ] Implement Eloquent model with relationships
- [ ] Enhance PropertyController with full CRUD operations
- [ ] Create ImageUploadService for file management
- [ ] Update frontend Alpine.js to connect with API
- [ ] Implement image upload and watermarking
- [ ] Add proper error handling and validation
- [ ] Create API documentation