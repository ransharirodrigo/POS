<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait ImageUploadTrait
{
    /**
     * Handle image upload and deletion of old image if exists.
     *
     * @param Request $request
     * @param string|null $oldImagePath
     * @param string $disk
     * @param string $directory
     * @return string|null
     */
    protected function handleImageUpload(Request $request, ?string $oldImagePath = null, string $disk = 'public', string $directory = 'images'): ?string
    {
        if (!$request->hasFile('image')) {
            return $oldImagePath;
        }

        $file = $request->file('image');

        if ($oldImagePath) {
            Storage::disk($disk)->delete($oldImagePath);
        }

        return $file->store($directory, $disk);
    }

    /**
     * Delete an image from storage.
     *
     * @param string|null $imagePath
     * @param string $disk
     * @return void
     */
    protected function deleteImage(?string $imagePath, string $disk = 'public'): void
    {
        if ($imagePath) {
            Storage::disk($disk)->delete($imagePath);
        }
    }
}
