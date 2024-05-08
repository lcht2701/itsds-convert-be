<?php

namespace App\Repositories\File;

interface IFileRepository
{
    public function uploadFile($file, $path);
    public function deleteFile($path);
}
