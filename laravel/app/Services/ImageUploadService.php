<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\log;

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
            // Check if GD extension is available
            if (!extension_loaded('gd')) {
                Log::warning("GD extension not available for image resizing");
                return;
            }

            $imageInfo = getimagesize($originalPath);
            if (!$imageInfo) {
                Log::warning("Invalid image file: {$originalPath}");
                return;
            }

            [$originalWidth, $originalHeight, $imageType] = $imageInfo;
            
            // Create image resource based on type
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $source = imagecreatefromjpeg($originalPath);
                    break;
                case IMAGETYPE_PNG:
                    $source = imagecreatefrompng($originalPath);
                    break;
                case IMAGETYPE_GIF:
                    $source = imagecreatefromgif($originalPath);
                    break;
                default:
                    Log::warning("Unsupported image type: {$imageType}");
                    return;
            }

            if (!$source) {
                Log::warning("Failed to create image resource from: {$originalPath}");
                return;
            }

            // Calculate aspect ratio and new dimensions
            $aspectRatio = $originalWidth / $originalHeight;
            $targetAspectRatio = $width / $height;

            if ($aspectRatio > $targetAspectRatio) {
                // Image is wider, fit by height
                $newHeight = $height;
                $newWidth = $height * $aspectRatio;
            } else {
                // Image is taller, fit by width
                $newWidth = $width;
                $newHeight = $width / $aspectRatio;
            }

            // Create resized image
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG and GIF
            if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // Resize image
            imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            
            // Generate new path for resized image
            $pathInfo = pathinfo($originalPath);
            $resizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . "_{$sizeName}." . $pathInfo['extension'];
            
            // Save resized image
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    imagejpeg($resized, $resizedPath, 90);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($resized, $resizedPath, 9);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($resized, $resizedPath);
                    break;
            }
            
            // Clean up
            imagedestroy($source);
            imagedestroy($resized);
            
            Log::info("Created {$sizeName} image: {$resizedPath}");
            
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
            
            // Check if GD extension is available
            if (!extension_loaded('gd')) {
                Log::warning("GD extension not available for watermark");
                return false;
            }

            $imageInfo = getimagesize($fullPath);
            if (!$imageInfo) {
                Log::warning("Invalid image file for watermark: {$fullPath}");
                return false;
            }

            [$width, $height, $imageType] = $imageInfo;
            
            // Create image resource
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($fullPath);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($fullPath);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($fullPath);
                    break;
                default:
                    Log::warning("Unsupported image type for watermark: {$imageType}");
                    return false;
            }

            if (!$image) {
                Log::warning("Failed to create image resource for watermark: {$fullPath}");
                return false;
            }

            // Create watermark color (white with black outline)
            $textColor = imagecolorallocate($image, 255, 255, 255);
            $outlineColor = imagecolorallocate($image, 0, 0, 0);
            
            // Calculate text position (bottom right)
            $fontSize = 5; // Built-in font
            $textWidth = imagefontwidth($fontSize) * strlen($watermarkText);
            $textHeight = imagefontheight($fontSize);
            
            $x = $width - $textWidth - 10;
            $y = $height - $textHeight - 10;
            
            // Add text outline
            for ($xOffset = -1; $xOffset <= 1; $xOffset++) {
                for ($yOffset = -1; $yOffset <= 1; $yOffset++) {
                    if ($xOffset != 0 || $yOffset != 0) {
                        imagestring($image, $fontSize, $x + $xOffset, $y + $yOffset, $watermarkText, $outlineColor);
                    }
                }
            }
            
            // Add main text
            imagestring($image, $fontSize, $x, $y, $watermarkText, $textColor);
            
            // Save watermarked image
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    imagejpeg($image, $fullPath, 90);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($image, $fullPath, 9);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($image, $fullPath);
                    break;
            }
            
            // Clean up
            imagedestroy($image);
            
            Log::info("Watermark applied to: {$imagePath}");
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

    /**
     * Upload property image for edit modal (returns complete data structure)
     */
    public function uploadPropertyImageForEdit(UploadedFile $image, int $userId, int $propertyId, int $order = 1): ?array
    {
        // Validate image first
        $validation = $this->validateImage($image);
        if (!$validation['valid']) {
            return null;
        }
        
        $originalName = $image->getClientOriginalName();
        $extension = $image->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        
        // Determine directory based on order (first image = primary)
        $directory = $order === 1 ? 'primary' : 'gallery';
        $fullDirectory = "properties/{$userId}/{$propertyId}/{$directory}";
        
        // Create directory if not exists
        Storage::disk('public')->makeDirectory($fullDirectory);
        
        // Store original image
        $path = $image->storeAs($fullDirectory, $fileName, 'public');
        
        // Generate different sizes
        $this->generateImageSizes($path);
        
        // Build full URL
        $url = Storage::disk('public')->url($path);
        
        return [
            'path' => $path,
            'url' => $url,
            'order' => $order,
            'is_primary' => $order === 1,
            'original_name' => $originalName,
            'size' => $image->getSize(),
            'mime_type' => $image->getMimeType(),
            'is_watermarked' => false,
        ];
    }

    /**
     * Validate uploaded image
     */
    public function validateImage(UploadedFile $image): array
    {
        $errors = [];
        
        // Check file size (max 10MB)
        $maxSize = 10 * 1024 * 1024; // 10MB
        if ($image->getSize() > $maxSize) {
            $errors[] = 'Image size must not exceed 10MB';
        }
        
        // Check file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!in_array($image->getMimeType(), $allowedTypes)) {
            $errors[] = 'Only JPEG, PNG, and WebP images are allowed';
        }
        
        // Check if image is valid and get dimensions
        $imageInfo = @getimagesize($image->getPathname());
        if (!$imageInfo) {
            $errors[] = 'Invalid image file';
        } else {
            [$width, $height] = $imageInfo;
            
            // Check max resolution (1920x1080 or one side max 1920px)
            if ($width > 1920 || $height > 1920) {
                $errors[] = 'Image dimensions must not exceed 1920x1080 pixels';
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}