<?php

namespace App\Repositories\File;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileRepository implements IFileRepository
{
    public function uploadFile($file, $path)
    {
        return $file->store($path, 'public');
    }

    public function deleteFile($path)
    {
        try {
            return Storage::disk('public')->deleteDirectory(dirname($path));
        } catch (Exception $e) {
            Log::error('Failed to delete file', ['path' => $path, 'error' => $e->getMessage()]);
            return false;
        }
    }
}
