<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Compress an uploaded image, convert it to WebP, and save it to storage.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $maxWidth
     * @param int $quality
     * @return string Saved file path relative to storage root (public)
     */
    public function compressAndSave(UploadedFile $file, string $directory = 'products', int $maxWidth = 800, int $quality = 75): string
    {
        // Ensure directory exists
        Storage::disk('public')->makeDirectory($directory);

        // Generate a unique filename with .webp extension
        $filename = Str::uuid() . '.webp';

        // Get original dimensions
        list($origWidth, $origHeight, $imageType) = getimagesize($file->getRealPath());

        // Calculate new dimensions (maintain aspect ratio)
        $width = $origWidth;
        $height = $origHeight;

        if ($origWidth > $maxWidth) {
            $width = $maxWidth;
            $height = (int) (($origHeight / $origWidth) * $maxWidth);
        }

        // Load image based on type
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($file->getRealPath());
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($file->getRealPath());
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($file->getRealPath());
                break;
            case IMAGETYPE_WEBP:
                $sourceImage = imagecreatefromwebp($file->getRealPath());
                break;
            default:
                // Fallback to standard save if unsupported type
                $path = $file->storeAs($directory, Str::uuid() . '.' . $file->getClientOriginalExtension(), 'public');
                return 'storage/' . $path;
        }

        if (!$sourceImage) {
            $path = $file->storeAs($directory, Str::uuid() . '.' . $file->getClientOriginalExtension(), 'public');
            return 'storage/' . $path;
        }

        // Create new true color image
        $resizedImage = imagecreatetruecolor($width, $height);

        // Preserve transparency for PNG/WebP
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);

        // Copy and resize
        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

        // Save as WebP using Storage facade to support faking/custom drivers
        ob_start();
        imagewebp($resizedImage, null, $quality);
        $webpData = ob_get_clean();
        Storage::disk('public')->put($directory . '/' . $filename, $webpData);

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        return 'storage/' . $directory . '/' . $filename;
    }

    /**
     * Convert an uploaded image to WebP format and return as base64 string.
     * Validates file size and compresses the image.
     *
     * @param UploadedFile $file
     * @param int $maxWidth Maximum width for the image
     * @param int $quality WebP quality (0-100)
     * @return string Base64 encoded WebP image with data URI prefix
     * @throws \Exception If image processing fails
     */
    public function convertToWebPBase64(UploadedFile $file, int $maxWidth = 400, int $quality = 85): string
    {
        // Get original dimensions
        $imageInfo = getimagesize($file->getRealPath());
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }

        list($origWidth, $origHeight, $imageType) = $imageInfo;

        // Calculate new dimensions (maintain aspect ratio)
        $width = $origWidth;
        $height = $origHeight;

        if ($origWidth > $maxWidth) {
            $width = $maxWidth;
            $height = (int) (($origHeight / $origWidth) * $maxWidth);
        }

        // Load image based on type
        $sourceImage = null;
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($file->getRealPath());
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($file->getRealPath());
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($file->getRealPath());
                break;
            case IMAGETYPE_WEBP:
                $sourceImage = imagecreatefromwebp($file->getRealPath());
                break;
            default:
                throw new \Exception('Unsupported image type');
        }

        if (!$sourceImage) {
            throw new \Exception('Failed to load image');
        }

        // Create new true color image
        $resizedImage = imagecreatetruecolor($width, $height);

        // Preserve transparency for PNG/WebP
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);

        // Copy and resize
        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

        // Convert to WebP and get binary data
        ob_start();
        imagewebp($resizedImage, null, $quality);
        $webpData = ob_get_clean();

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        // Convert to base64 with data URI prefix
        $base64 = base64_encode($webpData);
        return 'data:image/webp;base64,' . $base64;
    }
}
