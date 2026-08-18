<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Resize (downscale-only) and re-encode an uploaded image as WebP, store it, and
     * return its relative path on the given disk.
     */
    public static function optimize(UploadedFile $file, string $directory, int $maxDimension, string $disk = 'public'): string
    {
        return static::optimizePath($file->getRealPath(), $directory, $maxDimension, $disk);
    }

    /**
     * Same as optimize(), but for a file already sitting on disk (used to reprocess
     * previously-uploaded images that were stored before this optimizer existed).
     */
    public static function optimizePath(string $absolutePath, string $directory, int $maxDimension, string $disk = 'public'): string
    {
        $image = static::load($absolutePath);

        $image = static::fixOrientation($image, $absolutePath);
        $image = static::resize($image, $maxDimension);

        $path = trim($directory, '/') . '/' . Str::random(32) . '.webp';

        $tmp = tempnam(sys_get_temp_dir(), 'webp');
        imagewebp($image, $tmp, 82);
        imagedestroy($image);

        Storage::disk($disk)->put($path, file_get_contents($tmp));
        unlink($tmp);

        return $path;
    }

    private static function load(string $path): \GdImage
    {
        $data = file_get_contents($path);
        $image = imagecreatefromstring($data);

        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        return $image;
    }

    private static function fixOrientation(\GdImage $image, string $path): \GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($path);
        $orientation = $exif['Orientation'] ?? 1;

        return match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }

    private static function resize(\GdImage $image, int $maxDimension): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $maxDimension && $height <= $maxDimension) {
            return $image;
        }

        $ratio = min($maxDimension / $width, $maxDimension / $height);
        $newWidth = (int) round($width * $ratio);
        $newHeight = (int) round($height * $ratio);

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        return $resized;
    }
}
