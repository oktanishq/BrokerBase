<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LogoUploadService
{
    /**
     * Upload agency logo with validation and thumbnail generation
     */
    public function uploadLogo(UploadedFile $logo): array
    {
        // Validate before upload
        $validation = $this->validateLogo($logo);
        if (!$validation['valid']) {
            throw new \InvalidArgumentException(implode(', ', $validation['errors']));
        }

        // Generate unique filename
        $extension = $logo->getClientOriginalExtension();
        $fileName = 'logo_' . Str::uuid() . '.' . $extension;
        
        // Create logos directory
        $directory = 'logos';
        Storage::disk('public')->makeDirectory($directory);
        
        // Store original logo
        $path = $logo->storeAs($directory, $fileName, 'public');
        
        // Generate thumbnail for circular display
        $this->generateThumbnail($path);
        
        return [
            'path' => $path,
            'url' => Storage::url($path),
            'original_name' => $logo->getClientOriginalName(),
            'size' => $logo->getSize(),
        ];
    }

    /**
     * Delete existing logo file
     */
    public function deleteLogo(string $logoUrl): bool
    {
        // Convert URL back to path
        $path = str_replace('/storage/', '', parse_url($logoUrl, PHP_URL_PATH));
        
        if (Storage::disk('public')->exists($path)) {
            // Delete main logo
            Storage::disk('public')->delete($path);
            
            // Delete thumbnail if exists
            $pathInfo = pathinfo($path);
            $thumbnailPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
            Storage::disk('public')->delete($thumbnailPath);
            
            return true;
        }
        
        return false;
    }

    /**
     * Validate logo requirements
     */
    private function validateLogo(UploadedFile $logo): array
    {
        $errors = [];
        
        // Size check: max 2MB
        if ($logo->getSize() > 2 * 1024 * 1024) {
            $errors[] = 'Logo size must not exceed 2MB';
        }
        
        // Type check
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($logo->getMimeType(), $allowedTypes)) {
            $errors[] = 'Only JPEG, PNG, GIF, and WebP images are allowed';
        }
        
        // Minimum dimension check
        $imageInfo = @getimagesize($logo->getPathname());
        if ($imageInfo && ($imageInfo[0] < 100 || $imageInfo[1] < 100)) {
            $errors[] = 'Logo must be at least 100x100 pixels';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Generate 200x200 thumbnail for circular display
     */
    private function generateThumbnail(string $originalPath): void
    {
        try {
            if (!extension_loaded('gd')) {
                \Illuminate\Support\Facades\Log::warning("GD extension not available for logo thumbnail");
                return;
            }

            $fullPath = Storage::disk('public')->path($originalPath);
            $imageInfo = getimagesize($fullPath);
            
            if (!$imageInfo) {
                return;
            }

            [$width, $height, $imageType] = $imageInfo;
            
            // Create source image
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $source = imagecreatefromjpeg($fullPath);
                    break;
                case IMAGETYPE_PNG:
                    $source = imagecreatefrompng($fullPath);
                    break;
                case IMAGETYPE_GIF:
                    $source = imagecreatefromgif($fullPath);
                    break;
                default:
                    return;
            }

            if (!$source) {
                return;
            }

            // Create 200x200 thumbnail
            $thumbWidth = 200;
            $thumbHeight = 200;
            $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);
            
            // Preserve transparency
            if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
                $transparent = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
                imagefilledrectangle($thumbnail, 0, 0, $thumbWidth, $thumbHeight, $transparent);
            }

            // Resize maintaining aspect ratio, center-cropped to square
            $this->smartResize($source, $thumbnail, $width, $height, $thumbWidth, $thumbHeight);

            // Save thumbnail
            $pathInfo = pathinfo($fullPath);
            $thumbPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
            
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    imagejpeg($thumbnail, $thumbPath, 90);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($thumbnail, $thumbPath, 9);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($thumbnail, $thumbPath);
                    break;
            }

            imagedestroy($source);
            imagedestroy($thumbnail);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Logo thumbnail generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Smart resize with center crop
     */
    private function smartResize($source, $target, $srcW, $srcH, $dstW, $dstH): void
    {
        $ratio = $srcW / $srcH;
        $targetRatio = $dstW / $dstH;

        if ($ratio > $targetRatio) {
            // Source is wider - crop sides
            $newWidth = $srcH * $targetRatio;
            $offsetX = ($srcW - $newWidth) / 2;
            imagecopyresampled(
                $target, $source,
                0, 0,           // dst x, y
                $offsetX, 0,    // src x, y
                $dstW, $dstH,   // dst w, h
                $newWidth, $srcH // src w, h
            );
        } else {
            // Source is taller - crop top/bottom
            $newHeight = $srcW / $targetRatio;
            $offsetY = ($srcH - $newHeight) / 2;
            imagecopyresampled(
                $target, $source,
                0, 0,           // dst x, y
                0, $offsetY,    // src x, y
                $dstW, $dstH,   // dst w, h
                $srcW, $newHeight // src w, h
            );
        }
    }
}
