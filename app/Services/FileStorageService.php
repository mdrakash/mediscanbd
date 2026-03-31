<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageService
{
    public function storeUpload(UploadedFile $file, int $userId, string $type): array
    {
        // Directory: uploads/{userId}/{type}/
        $directory = "uploads/{$userId}/{$type}/";

        // Filename: {timestamp}_{random}_{original_name}
        $timestamp = now()->timestamp;
        $random = Str::random(8);
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filename = "{$timestamp}_{$random}_{$originalName}.{$extension}";

        // Store in storage/app/private/
        $path = $file->storeAs($directory, $filename, 'private');

        return [
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
        ];
    }

    public function getFullPath(string $storagePath): string
    {
        return storage_path('app/private/'.$storagePath);
    }

    public function delete(string $storagePath): void
    {
        Storage::disk('private')->delete($storagePath);
    }
}
