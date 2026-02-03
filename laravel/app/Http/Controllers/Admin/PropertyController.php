<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    protected $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Display a listing of properties (for admin inventory)
     */
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $query = Property::with('user:id,name')
            ->forUser($userId)
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

        // Transform properties to match frontend expectations
        $transformedProperties = $properties->map(function ($property) {
            return $property->frontend_data;
        });

        return response()->json([
            'success' => true,
            'data' => $transformedProperties->toArray(),
            'pagination' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'per_page' => $properties->perPage(),
                'total' => $properties->total(),
            ]
        ]);
    }

    /**
     * Show the property creation form
     */
    public function create()
    {
        return view('admin.properties.create');
    }

    /**
     * Store a newly created property
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|in:apartment,villa,plot,commercial',
            'price' => 'nullable|numeric|min:0',
            'area_sqft' => 'nullable|integer|min:0',
            'net_price' => 'nullable|numeric|min:0',
            'address' => 'nullable|string', // Made optional to match frontend
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'maps_embed_url' => 'nullable|url',
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
            // Handle amenities as JSON string from FormData
            'amenities' => 'nullable|json',
        ]);

        // Parse amenities JSON string if it exists
        if (isset($validated['amenities']) && is_string($validated['amenities'])) {
            $validated['amenities'] = json_decode($validated['amenities'], true) ?? [];
        }

        try {
            $property = Property::create([
                ...$validated,
                'user_id' => Auth::id(),
                'status' => $validated['status'] ?? 'draft',
                'is_featured' => $validated['is_featured'] ?? false,
                'label_type' => $validated['label_type'] ?? 'none',
                'custom_label_color' => $validated['custom_label_color'] ?? '#3B82F6',
                'watermark_enabled' => $validated['watermark_enabled'] ?? true,
                'amenities' => $validated['amenities'] ?? [],
            ]);

            // Handle image uploads
            if ($request->hasFile('images') && !empty($request->file('images'))) {
                $images = $request->file('images');
                // Handle both single file and array of files
                if (!is_array($images)) {
                    $images = [$images];
                }
                $this->handleImageUploads($property, $images);
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
        // Check ownership
        if ($property->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

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
        // Check ownership
        if ($property->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'sometimes|required|in:apartment,villa,plot,commercial',
            'price' => 'nullable|numeric|min:0',
            'area_sqft' => 'nullable|integer|min:0',
            'net_price' => 'nullable|numeric|min:0',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'maps_embed_url' => 'nullable|url',
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
            if ($request->hasFile('images') && !empty($request->file('images'))) {
                $images = $request->file('images');
                // Handle both single file and array of files
                if (!is_array($images)) {
                    $images = [$images];
                }
                $this->handleImageUploads($property, $images);
            }

            return response()->json([
                'success' => true,
                'message' => 'Property updated successfully',
                'data' => $property->fresh()->frontend_data
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
        // Check ownership
        if ($property->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

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
     * Store draft property data
     */
    public function storeDraft(Request $request): JsonResponse
    {
        // For draft saving, we can save partial data
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'nullable|in:apartment,villa,plot,commercial',
            'price' => 'nullable|numeric|min:0',
            'area_sqft' => 'nullable|integer|min:0',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'maps_embed_url' => 'nullable|url',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'amenities' => 'nullable|array',
        ]);

        try {
            $property = Property::create([
                ...$validated,
                'user_id' => Auth::id(),
                'status' => 'draft',
                'is_featured' => false,
                'label_type' => 'none',
                'custom_label_color' => '#3B82F6',
                'watermark_enabled' => true,
                'amenities' => $validated['amenities'] ?? [],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully',
                'data' => $property
            ], 201);

        } catch (\Exception $e) {
            Log::error('Draft saving failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save draft'
            ], 500);
        }
    }

    /**
     * Handle image uploads
     * Uses single column approach - stores all images in 'images' JSON column
     */
    private function handleImageUploads(Property $property, array $images): void
    {
        $userId = Auth::id();
        $propertyId = $property->id;
        $uploadedImages = [];

        foreach ($images as $index => $image) {
            if (!$image->isValid()) {
                Log::warning("Invalid image file at index {$index}");
                continue;
            }

            // Validate image using the service
            $validation = $this->imageUploadService->validateImage($image);
            if (!$validation['valid']) {
                Log::warning("Image validation failed: " . implode(', ', $validation['errors']));
                continue;
            }

            try {
                // Use the ImageUploadService to handle the upload
                $uploadResult = $this->imageUploadService->uploadPropertyImages($image, $userId, $propertyId, $index + 1);
                
                // Add watermark if enabled
                if ($property->watermark_enabled) {
                    $this->imageUploadService->applyWatermark($uploadResult['path']);
                }
                
                // Store metadata in new format
                $uploadedImages[] = [
                    'path' => $uploadResult['path'],
                    'original_name' => $uploadResult['original_name'],
                    'size' => $uploadResult['size'],
                    'mime_type' => $uploadResult['mime_type'],
                    'order' => $index + 1,
                    'is_watermarked' => $property->watermark_enabled,
                    'is_primary' => $index === 0,
                ];
                
                Log::info("Image uploaded successfully: {$uploadResult['path']}");
                
            } catch (\Exception $e) {
                Log::error("Image upload failed for index {$index}: " . $e->getMessage());
                continue;
            }
        }
        
        // Save images using the Property model's single column approach
        if (!empty($uploadedImages)) {
            $property->saveImages($uploadedImages, $property->watermark_enabled);
            Log::info("Updated property {$propertyId} with " . count($uploadedImages) . " images");
        }
    }



    /**
     * Delete all property images
     * Uses single column approach - reads from 'images' JSON column
     */
    private function deletePropertyImages(Property $property): void
    {
        try {
            $imagePaths = [];
            
            // Collect all image paths from single 'images' column
            $allImages = $property->all_images;
            
            if (!empty($allImages) && is_array($allImages)) {
                foreach ($allImages as $image) {
                    if (isset($image['path'])) {
                        $imagePaths[] = $image['path'];
                    }
                }
            }
            
            // Use ImageUploadService to delete all images and their variants
            if (!empty($imagePaths)) {
                $this->imageUploadService->deletePropertyImages($imagePaths);
                Log::info("Deleted " . count($imagePaths) . " images for property {$property->id}");
            }
            
            // Delete directory if empty
            $userId = $property->user_id;
            $propertyId = $property->id;
            $directoryPath = "properties/{$userId}/{$propertyId}";
            Storage::disk('public')->deleteDirectory($directoryPath);
            
        } catch (\Exception $e) {
            Log::warning('Image deletion failed: ' . $e->getMessage());
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

    /**
     * Update property status (for edit modal)
     */
    public function updateStatus(Request $request, Property $property): JsonResponse
    {
        if ($property->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,available,booked,sold',
            'is_featured' => 'nullable|boolean',
            'label_type' => 'nullable|in:none,new,popular,verified,custom',
            'custom_label_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        try {
            $property->update($validated);
            
            // Handle status transitions
            $this->handleStatusTransition($property, $validated['status']);

            return response()->json([
                'success' => true,
                'message' => 'Property status updated successfully',
                'data' => $property->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Status update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update property status'
            ], 500);
        }
    }
}