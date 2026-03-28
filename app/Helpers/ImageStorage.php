<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageStorage
{
    /**
     * Upload an image to the R2 storage.
     *
     * @param  UploadedFile  $file
     * @param  string  $folder
     * @param  string|null  $disk
     * @return string  The path to the uploaded file.
     */
    public static function upload(UploadedFile $file, string $folder, ?string $disk = null): string
    {
        $disk = $disk ?? config('filesystems.default');
        
        // Generate a unique filename to avoid collisions
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        
        return $file->storeAs($folder, $filename, [
            'disk' => $disk,
            'visibility' => 'public'
        ]);
    }

    /**
     * Upload raw data to the storage.
     *
     * @param  string  $data
     * @param  string  $path
     * @param  string|null  $disk
     * @return bool
     */
    public static function put(string $data, string $path, ?string $disk = null): bool
    {
        $disk = $disk ?? config('filesystems.default');

        return Storage::disk($disk)->put($path, $data, 'public');
    }

    /**
     * Upload a base64 encoded image.
     *
     * @param  string  $base64Data
     * @param  string  $folder
     * @param  string|null  $disk
     * @return string|null  The path to the uploaded file.
     */
    public static function uploadBase64(string $base64Data, string $folder, ?string $disk = null): ?string
    {
        if (!$base64Data) {
            return null;
        }

        $photoData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Data);
        $photoData = str_replace(' ', '+', $photoData);
        $decodedData = base64_decode($photoData);
        
        $filename = Str::random(40) . '.jpg';
        $path = $folder . '/' . $filename;

        self::put($decodedData, $path, $disk);

        return $path;
    }

    /**
     * Delete an image from the storage.
     *
     * @param  string|null  $path
     * @param  string|null  $disk
     * @return bool
     */
    public static function delete(?string $path, ?string $disk = null): bool
    {
        if (!$path) {
            return false;
        }

        $disk = $disk ?? config('filesystems.default');

        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }

    /**
     * Get the full URL of the image.
     *
     * @param  string|null  $path
     * @param  string|null  $disk
     * @return string|null
     */
    public static function url(?string $path, ?string $disk = null): ?string
    {
        if (!$path) {
            return null;
        }

        // If it's already a full URL, return it
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $disk = $disk ?? config('filesystems.default');

        return Storage::disk($disk)->url($path);
    }
}
